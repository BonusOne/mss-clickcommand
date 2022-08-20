<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

use App\Entity\ClickStatistics;
use App\Service\BufferRow;
use App\Service\MainBufferNew;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\CompanyService;

class BufferParserNew extends AbstractController
{
    /** @var ObjectManager $entityManager */
    private $entityManager;

    /** @var MainBufferNew $mainBuffer */
    private $mainBuffer;

    /** @var BufferRow $bufferRow */
    private $bufferRow;

    /** @var LoggerInterface $logger */
    private $logger;

    private $bufferDirectory;
    private $projectDir;

    public function __construct(LoggerInterface $logger, MainBufferNew $mainBuffer, BufferRow $bufferRow, string $projectDir)
    {
        $this->logger = $logger;
        //$this->entityManager = $this->getDoctrine()->getManager()->getConnection();
        $this->mainBuffer = $mainBuffer;
        $this->bufferRow = $bufferRow;
        $this->projectDir = $projectDir;
        $this->bufferDirectory = $this->projectDir. '/var/buffer/';
        if (!file_exists($this->bufferDirectory)) {
            mkdir($this->bufferDirectory, 0777, true);
        }

    }

    /**
     * @return int
     */
    private function getRowParserLimit()
    {
        return 10000;
    }

    /**
     * @return int
     */
    private function getBatchSize()
    {
        return 100;
    }

    /**
     * @return int
     */
    private function getLastBuildTimeFilePath()
    {
        return $this->bufferDirectory. 'last_build_time';
    }

    /**
     * @return int
     */
    function getLastBuildTime()
    {
        $lastBuildTimePath = $this->getLastBuildTimeFilePath();

        return file_exists($lastBuildTimePath) ? intval(
            file_get_contents(
                $lastBuildTimePath
            )
        ) : 0;
    }

    /**
     * @param bool|float $timestamp
     */
    function setLastBuildTime($timestamp = false)
    {
        $lastBuildTimePath = $this->getLastBuildTimeFilePath();
        $ts = $timestamp ? $timestamp : microtime(true);
        file_put_contents($lastBuildTimePath, $ts);
    }

    /**
     * @return bool
     */
    private function prepareBufferFileToParsing()
    {
        if (!$this->isTempBufferExist()) {
            if ($this->isMainBufferExist()) {
                $this->moveMainBufferFileToParsingFile();
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isMainBufferExist()
    {
        return file_exists($this->mainBuffer->getFilePath());
    }

    /**
     * @return bool
     */
    private function isTempBufferExist()
    {
        return file_exists($this->mainBuffer->getTempBufferFilePath());
    }

    /**
     * @return bool
     */
    private function moveMainBufferFileToParsingFile()
    {
        if ($this->isMainBufferExist()) {
            rename(
                $this->mainBuffer->getFilePath(), $this->mainBuffer->getTempBufferFilePath()
            );

            return true;
        }

        return false;
    }

    /**
     * @param OutputInterface $output
     * @return bool
     * @throws \Exception
     */
    public function buildData(OutputInterface $output)
    {
        if (!$this->prepareBufferFileToParsing()) {
            $output->writeln("No buffer file found");

            return false;
        }

        //$parsingBufferFile = new \SplFileObject($this->mainBuffer->getTempBufferFilePath(), 'r');
        $parsingBufferFile = fopen($this->mainBuffer->getTempBufferFilePath(), "r");

        if (flock($parsingBufferFile, LOCK_EX)) {

            $numberOfParsedRows = 0;
            $numberOfErrorRows = 0;
            $insertData = [];

            while (!feof($parsingBufferFile)) {
                $line = fgets($parsingBufferFile);

                //$bufferRow = new BufferRow($this->logger, $this->uaparser);
                try {
                    $this->bufferRow->loadDataFromJson($line);
                    $insertData[] = $this->bufferRow->prepareParamsToSave();

                    //$this->bufferRow = null;
                } catch (\Exception $exception) {
                    $this->addErrorParsedRow($line, $exception->getMessage());
                    $numberOfErrorRows++;
                }
                $numberOfParsedRows++;

                $saveSuccess = $this->saveDataToStatistics($insertData);
                if ($saveSuccess) {
                    $insertData = [];
                } else {
                    $this->logger->error("Failed to save parser rows to database", (array) 'App\Service\BufferParser');
                    break;
                }
                $output->writeln("Parsed: ".$numberOfParsedRows." rows. Added: ".($numberOfParsedRows - $numberOfErrorRows).". Wrong data: ".$numberOfErrorRows."\r");

            }

            $output->writeln("Trying to remove temp buffer\n");
            flock($parsingBufferFile, LOCK_UN);
            fclose($parsingBufferFile);
            $parsingBufferFile = null;
            $this->mainBuffer->removeTempBuffer();

            //MainBufferOld::removeFirstNLinesFromFile($parsingBufferFile, BufferParserNew::getRowParserLimit());

            $output->writeln("Added: ".$numberOfParsedRows."; Bad rows: ".$numberOfErrorRows." \n");

        } else {
            $this->logger->error("Unable to lock file", (array) 'App\Service\BufferParser');

            return false;
        }

        return true;
    }

    /**
     * @param $data []
     * @return bool
     */
    private function saveDataToStatistics($data)
    {
        if (count($data) > 0) {
            //file_put_contents('Data_Parser.txt', var_export($data, true));
            $entityManager = $this->getDoctrine()->getManager();
            $this->getDoctrine()->resetManager();
            try {
                $batchSize = $this->getBatchSize();
                $forLimit = $this->getRowParserLimit() > (count($data)-1) ? (count($data)-1) : $this->getRowParserLimit();
                for ($i = 0; $i <= $forLimit; ++$i) {
                    $datas = $data[$i]['date'] ? new \DateTime($data[$i]['date']) : new \DateTime('now');
                    $dataTime = new \DateTime(date('Y-m-d H:i:s', $data[$i]['timestamp']));
                    $statistics = new ClickStatistics();
                    $statistics->setRedirectId($data[$i]['redirect_id'] ? $data[$i]['redirect_id'] : 0);
                    $statistics->setIdSmartInsertion($data[$i]['id_smart_insertion']);
                    $statistics->setLp($data[$i]['lp'] ? $data[$i]['lp'] : 0);
                    $statistics->setTimestamp(\DateTime::createFromFormat('Y-m-d H:i:s',$dataTime->format('Y-m-d H:i:s')));
                    $statistics->setIdCompany($data[$i]['id_company']);
                    $statistics->setIpv4($data[$i]['ipv4']);
                    $statistics->setDate(\DateTime::createFromFormat('Y-m-d H:i:s',$datas->format('Y-m-d H:i:s')));
                    $statistics->setReferer($data[$i]['referer']);
                    $statistics->setBrowser($data[$i]['browser']);
                    $statistics->setOperatingSystem($data[$i]['operating_system']);
                    $statistics->setDevice($data[$i]['device']);
                    $statistics->setRenderingEngine($data[$i]['rendering_engine']);
                    $statistics->setUseragent($data[$i]['useragent']);
                    $entityManager->persist($statistics);
                    if (($i % $batchSize) === 0) {
                        $entityManager->flush();
                        $entityManager->clear(); // Detaches all objects from Doctrine!
                    }
                }
                $entityManager->flush(); //Persist objects that did not make up an entire batch
                $entityManager->clear();

                return true;
            } catch (\Exception $e) {
                $this->logger->error($e->getCode().' -> '.$e->getMessage());

                return false;
            }
        } else {
            return true;
        }
    }


    private function addErrorParsedRow($row, $message)
    {
        $errorLogFile = new \SplFileObject($this->mainBuffer->getErrorLogsFilePath(), 'a+');
        $data = json_decode($row, true);
        $data['reason'] = $message;
        $errorLogFile->fwrite(json_encode($data) . PHP_EOL);
        $errorLogFile = null;
        $data = null;
    }
}