<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
//use UAParser\UAParser;

class BufferRow
{
    /** @var LoggerInterface $logger */
    private $logger;

    /*/** @var UAParser $uaparser */
    //private $uaparser;

    /**
     * @var int
     */
    private $cacheDuration = 300;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $inputData = [];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->params = [
            'id_smart_insertion' => null,
            'id_company' => null,
            'lp' => null,
            'timestamp' => null,
            'ipv4' => null,
            'date' => null,
            'referer' => null,
            'browser' => null,
            'operating_system' => null,
            'device' => null,
            'rendering_engine' => null,
            'useragent' => null,
            'redirect_id' => null,
        ];
    }

    /**
     * @param      $name
     * @param null $alternative
     *
     * @return mixed|null
     */
    private function getInputData($name, $alternative = null)
    {
        return isset($this->inputData[$name]) ? $this->inputData[$name]
            : $alternative;
    }

    /**
     * @param string $name
     * @param string|integer $value
     *
     * @return mixed
     * @throws \Exception
     */
    private function setParam($name, $value)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException(
                "Invalid param name " . var_export($name)
            );
        }
        if (!array_key_exists($name, $this->params)) {
            throw new \Exception("Wrong parameter BufferRow::$name");
        }
        $this->params[$name] = $value;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function getParam($name)
    {
        if (!array_key_exists($name, $this->params)) {
            throw new \Exception("Wrong parameter BufferRow::$name");
        }

        return $this->params[$name];
    }

    /**
     * @return array
     *
     * @return mixed
     * @throws \Exception
     */
    public function prepareParamsToSave()
    {
        if ($this->getParam('date') === null) {
            throw new InvalidArgumentException('Invalid date param');
        }
        return $this->params;
    }

    /**
     * @param $line
     * @throws \Exception
     */
    public function loadDataFromJson($line)
    {
        $this->inputData = \json_decode($line, true);

        $this->setParam('id_smart_insertion', $this->getInputData('id_smart_insertion'));
        $this->setParam('id_company', $this->getInputData('id_company'));
        $this->setParam('timestamp', $this->getInputData('timestamp'));
        $this->setParam('date', $this->getInputData('date'));
        $this->setParam('referer', $this->getInputData('referer'));

        if ($this->getInputData('redirect_id') !== null) {
            $this->setParam('redirect_id', $this->getInputData('redirect_id'));
        } else {
            $this->setParam('redirect_id', null);
        }

        if ($this->getInputData('lp') !== null) {
            $this->setParam('lp', $this->getInputData('lp'));
        } else {
            $this->setParam('lp', null);
        }

        if ($this->getInputData('ip') !== null) {
            //$this->setParam('ipv4', ip2long($this->getInputData('ip')));
            $this->setParam('ipv4', $this->getInputData('ip'));
        } else {
            $this->setParam('ipv4', null);
        }

        $this->parseBrowserUserAgentString();
    }

    /**
     * @throws \Exception
     */
    private function parseBrowserUserAgentString()
    {
        $this->setParam('useragent', $this->getInputData('useragent'));

        if ($this->getParam('useragent') === null) {
            return;
        }

        /*$browsCap = new UAParser();
        $browser = $browsCap->parse($this->params['useragent']);
        $browsCap = null;*/

//        $this->setParam('browser', $browser->getBrowser()->getFamily().' '.$browser->getBrowser()->getVersionString());
//        $this->setParam('operating_system', $browser->getOperatingSystem()->getFamily().' '.$browser->getOperatingSystem()->getMajor().'.'.$browser->getOperatingSystem()->getMinor().'.'.$browser->getOperatingSystem()->getPatch());
//        $this->setParam('device', $browser->getDevice()->getConstructor().' '.$browser->getDevice()->getModel().' '.$browser->getDevice()->getType());
//        $this->setParam('rendering_engine', $browser->getRenderingEngine()->getFamily().' '.$browser->getRenderingEngine()->getVersion());
        $this->setParam('browser', null);
        $this->setParam('operating_system', null);
        $this->setParam('device', null);
        $this->setParam('rendering_engine', null);
    }

}