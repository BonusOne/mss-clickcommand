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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Dotenv\Dotenv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Service\ActivityService;
use App\Service\CompanyService;
use Psr\Log\LoggerInterface;
use App\Repository\RedirectDataRepository;

/**
 * @Route("/tracking", name="tracking_")
 */
class trackingController extends AbstractController
{
    /** @var LoggerInterface $logger */
    private $logger;

    /** @var ActivityService $activity */
    private $activity;

    /** @var RedirectDataRepository */
    private $redirectDataRepository;

    private $dbcon;

    public function __construct(LoggerInterface $logger, ActivityService $activity, RedirectDataRepository $redirectDataRepository)
    {
        $this->logger = $logger;
        $this->activity = $activity;
        $this->redirectDataRepository = $redirectDataRepository;
        //$this->dbcon = $this->getDoctrine()->getManager()->getConnection();
    }

    /**
    * @Route("/", name="index")
    */
    public function index()
    {
        $response = new Response(
            'Tracking index of MSS System',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
        return $response;
    }

    /**
     * @Route("/click/{company_id}/{id_smart_insertion}/{id_redirect}/{timestamp}", name="click", methods={"GET"})
     * @param Request   $request
     * @param int       $company_id     Company ID -> CompanyService::TYPE_
     * @param int       $id_smart_insertion       Smart ID insertion is a ID insertion of SmartAdServer
     * @param int       $timestamp      Timestamp of datetime
     * @param int       $id_redirect    Id of redirect data
     * @return JsonResponse|Response
     */
    public function click(Request $request, int $company_id, int $id_smart_insertion, int $timestamp, int $id_redirect)
    {
        $data['id_smart_insertion'] = $id_smart_insertion;
        $data['id_company'] = $company_id;
        $data['timestamp'] = $timestamp;
        $data['redirect_id'] = $id_redirect;
        $data['useragent'] = $request->headers->get('User-Agent');

        $redirect_data = $this->redirectDataRepository->find($id_redirect);
        $redirect_url = $redirect_data->getUrl();

        $this->activity->addRow($data);

        return $this->redirect($redirect_url, 302);
    }
}