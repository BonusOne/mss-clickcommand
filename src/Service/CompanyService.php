<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

use Psr\Log\LoggerInterface;

class CompanyService
{
    /** @var LoggerInterface $logger */
    private $logger;

    private $dbcon;

    const TYPE_DEV = 1;

    const TYPE_PROD = 2;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        //$this->dbcon = $this->getDoctrine()->getManager()->getConnection();
    }
}