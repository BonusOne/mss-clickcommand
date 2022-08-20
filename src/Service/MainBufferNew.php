<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class MainBufferNew
{
    /** @var KernelInterface $appKernel */
    private $appKernel;
    private $bufferDirectory;
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->bufferDirectory = $this->projectDir. '/var/buffer/';
        if (!file_exists($this->bufferDirectory)) {
            mkdir($this->bufferDirectory, 0777, true);
        }
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->bufferDirectory. 'action_buffer.bin';
    }

    /**
     * @return string
     */
    public function getErrorLogsFilePath()
    {
        return $this->bufferDirectory. 'error_parsed_rows.bin';
    }

    /**
     * @return string
     */
    public function getTempBufferFilePath()
    {
        return $this->bufferDirectory. 'temp_action_buffer_new.bin';
    }

    /**
     * @return string
     */
    public function getTempBufferSplitFilePath()
    {
        return $this->bufferDirectory. 'temp_action_split_buffer_new.bin';
    }

    /**
     * @return bool
     */
    function removeTempBuffer()
    {
        if (file_exists($this->getTempBufferFilePath())) {
            unlink($this->getTempBufferFilePath());

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function removeBuffer()
    {
        if (file_exists($this->getFilePath())) {
            unlink($this->getFilePath());

            return true;
        }

        return false;
    }


    /**
     * @param $file \SplFileObject
     * @param $numberOfLines
     */
    public function removeFirstNLinesFromFile(&$file, $numberOfLines)
    {
        /*$tempSplitFile = new \SplFileObject(
            $this->getTempBufferSplitFilePath(), 'w'
        );

        foreach (
            new \LimitIterator($file, $numberOfLines) as
            $line
        ) {
            $tempSplitFile->fwrite($line);
        }

        $file->flock(LOCK_UN);
        $file = null;
        $tempSplitFile = null;

        rename($this->getTempBufferSplitFilePath(), $this->getTempBufferFilePath());

        $tempSplitFile = null;

        $file = new \SplFileObject($this->getTempBufferFilePath(), 'r');
        $file->flock(LOCK_EX);*/
        $lines = file($this->getTempBufferFilePath());
        $first_line = $lines[0];
        $lines = array_slice($lines, $numberOfLines);
        $lines = array_merge(array($first_line, "\n"), $lines);

        // Write to file
        $file = fopen($this->getTempBufferFilePath(), 'w');
        fwrite($file, implode('', $lines));
        fclose($file);
    }
}
