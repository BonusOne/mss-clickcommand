<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class indexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $response = new Response(
            'Index of MSS System',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
        return $response;
    }
}