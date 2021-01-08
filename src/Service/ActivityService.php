<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

use App\Service\MainBuffer;
use Psr\Log\LoggerInterface;

class ActivityService
{
    /** @var LoggerInterface $logger */
    private $logger;

    /** @var MainBuffer $mainBuffer */
    private $mainBuffer;

    private $dbcon;

    public function __construct(LoggerInterface $logger, MainBuffer $mainBuffer)
    {
        $this->logger = $logger;
        $this->mainBuffer = $mainBuffer;
        //$this->dbcon = $this->getDoctrine()->getManager()->getConnection();
    }

    /**
     * @return string|null
     */
    private function getRequestReferer()
    {
        if (isset($_SERVER["HTTP_REFERER"])) {
            return $_SERVER["HTTP_REFERER"];
        } else if (isset($_SESSION["origURL"])) {
            return $_SESSION["origURL"];
        }

        return null;
    }

    /**
     * @param $data
     * @return array
     */
    private function prepareData($data)
    {
        return array_merge(
            [
                'date' => date("Y-m-d H:i:s"),
                'ip' => isset($_SERVER['HTTP_CF_CONNECTING_IP'])
                    ? $_SERVER['HTTP_CF_CONNECTING_IP']
                    : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR']
                        : null),
                'referer' => $this->getRequestReferer(),
            ], $data
        );
    }

    /**
     * @param   $data
     * @return  bool
     */
    public function addRow($data)
    {
        $data = $this->prepareData($data);
        $bufferRow = json_encode($data) . PHP_EOL;

        try {
            $fp = @fopen($this->mainBuffer->getFilePath(), 'a');
            @fwrite($fp, $bufferRow);
            @fclose($fp);

            return true;
        } catch (\Exception $e) {
            $this->logger->error('[ERROR] Add row - Activity: '. $e->getMessage());

            return false;
        }
    }
}