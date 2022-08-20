<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Command;

use App\Service\BufferParserNew;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class buildBufferNewCommand extends Command
{
    /** @var BufferParserNew $bufferParser */
    private $bufferParser;

    /** @var LoggerInterface $logger */
    private $logger;

    private $buildIntervalTime;

    public function __construct(LoggerInterface $logger, BufferParserNew $bufferParser, string $buildIntervalTime)
    {
        $this->logger = $logger;
        $this->bufferParser = $bufferParser;
        $this->buildIntervalTime = $buildIntervalTime;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('mss:build-buffer-new')
            ->setDescription('Build new buffered statistics to save in database.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $ignoreInterval
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output, $ignoreInterval = false)
    {
        $tts = $this->buildIntervalTime;

        $lastBuildTime = $this->bufferParser->getLastBuildTime();

        if (((microtime(true) - $lastBuildTime) > $tts) || $ignoreInterval) {
            if (!$ignoreInterval) {
                $this->bufferParser->setLastBuildTime();
            }
            $output->writeln("Building (" . date("Y-m-d H:i:s") . ")");
            $this->bufferParser->buildData($output);
        } else {
            $ttw = ($tts - (microtime(true) - $lastBuildTime));
            $output->writeln("Waiting: " . ceil($ttw) . " s");
            sleep(ceil($ttw));
        }

        $command = $this->getApplication()->find('mss:build-buffer-new');
        $returnCode = $command->run($input,$output);

        return $returnCode;
    }
}
