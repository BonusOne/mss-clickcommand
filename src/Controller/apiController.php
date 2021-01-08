<?php
/**
 * MSS - MicroService Statistics
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2020  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Controller;

use App\Repository\RedirectDataRepository;
use App\Repository\ClickStatisticsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Dotenv\Dotenv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\RedirectData;
use App\Service\CompanyService;

/**
 * @Route("/api", name="api_")
 */
class apiController extends AbstractController
{
    /** @var ObjectManager */
    private $entityManager;

    /** @var RedirectDataRepository */
    private $redirectDataRepository;

    /** @var ClickStatisticsRepository */
    private $clickStatisticsRepository;

    /**
     * apiController constructor.
     * @param RedirectDataRepository $redirectDataRepository
     * @param ClickStatisticsRepository $clickStatisticsRepository
     */
    public function __construct(RedirectDataRepository $redirectDataRepository, ClickStatisticsRepository $clickStatisticsRepository)
    {
        $this->redirectDataRepository = $redirectDataRepository;
        $this->clickStatisticsRepository = $clickStatisticsRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $response = new Response(
            'Api index of MSS System',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
        return $response;
    }

    /**
     * @Route("/get_urls/{id_smart_campaign}", name="get_data", methods={"GET"})
     * @param Request   $request
     * @param int       $id_smart_campaign       Smart ID campaign is a ID campaign of SmartAdServer
     * @return JsonResponse|Response
     */
    public function getData(Request $request, int $id_smart_campaign)
    {
        /*$date_from = $request->query->get('date_from').' 00:00:00';
        $date_to = $request->query->get('$date_to').' 23:59:59';*/
        $redirect_data = $this->redirectDataRepository->findBySmartCampaign($id_smart_campaign);
        $responseData = array();
        $using_smart = false;
        foreach($redirect_data as $key => $value){
            if($value['using_smart'] == 1){
                $using_smart = true;
            }
        }
        if(!$using_smart) {
            foreach ($redirect_data as $key => $value) {
                $responseData[$key]['lp'] = $value['lp'];
                $responseData[$key]['name'] = $value['name'];
                $responseData[$key]['url'] = 'https://' . $_SERVER['HTTP_HOST'] . '/tracking/click/' . CompanyService::TYPE_PROD . '/[id_smart_insertion]/' . $value['id'] . '/[timestamp]';
            }
            usort($responseData, function ($item1, $item2) {
                return $item1['lp'] <=> $item2['lp'];
            });
        } else {
            $responseData['using_smart'] = true;
        }
        if($redirect_data != NULL or !empty($redirect_data)){
            return new JsonResponse(['Success' => true, 'data' => $responseData]);
        } else {
            return new JsonResponse(['Success' => false, 'info' => 'Get data api of MSS System']);
        }

    }

    /**
     * @Route("/get_statistics/{id_smart_insertion}", name="get_statistics")
     * @param Request   $request
     * @param int       $id_smart_insertion       Smart ID insertion is a ID campaign of SmartAdServer
     * @return JsonResponse|Response
     */
    public function getStatistics(Request $request, int $id_smart_insertion)
    {
        $content = $request->request->all();
        $date_from = $content['date_from'];
        $date_to = $content['date_to'];
        $var = $this->getDoctrine()->getManager()
            ->getConnection()
            ->prepare('call getStatisticsInsertionWithData('.$id_smart_insertion.',"'.$date_from.'","'.$date_to.'")');
        $var->execute();
        $redirect_data = $var->fetchAll();
        foreach ($redirect_data as $key => $value) {
            $redirect_data[$key]['creative'] = 'creative_'.($redirect_data[$key]['lp']+5);
        }
        //file_put_contents('data.txt', var_export($redirect_data, true));
        if($redirect_data != NULL){
            return new JsonResponse(['Success' => true, 'data' => $redirect_data]);
        } else {
            return new JsonResponse(['Success' => false, 'info' => 'Get data api of MSS System']);
        }

    }

    /**
     * @Route("/redirect_data_add", name="redirect_data_add")
     * @param Request $request
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function redirectDataAdd(Request $request)
    {
        //$content = $request->getContent();
        $content = $request->request->all();
        //file_put_contents('filename.txt', var_export($content, true));
        if (empty($content) or $content == NULL) {
            return new JsonResponse(['Success' => false, 'info' => new BadRequestHttpException()]);
        }

        if(!empty($content['id_campaign_smart']) or $content['id_campaign_smart'] != '') {
            $entityManager = $this->getDoctrine()->getManager();
            $data = new \DateTime('now');
            $redirectData = new RedirectData();
            $redirectData->setLp($content['lp']);
            $redirectData->setName($content['name']);
            $redirectData->setUrl($content['url']);
            $redirectData->setIdSmartCampaign($content['id_campaign_smart']);
            $redirectData->setIdTracklyCampaign($content['id_campaign_trackly']);
            $redirectData->setIdLiwochaCampaign($content['id_campaign_liwocha']);
            $redirectData->setDate(\DateTime::createFromFormat('Y-m-d H:i:s',$data->format('Y-m-d H:i:s')));
            $redirectData->setDeleted(0);
            $redirectData->setUsingSmart($content['using_smart']);
            $entityManager->persist($redirectData);
            $entityManager->flush();

            return new JsonResponse(['Success' => true,'info' => 'Success add data to api', 'id_redirect' => $redirectData->getId()]);
        } else {
            return new JsonResponse(['Success' => false, 'info' => 'No id campaign']);
        }
    }

    /**
     * @Route("/redirect_data_delete/{id_redirect}", name="redirect_data_delete")
     * @param int   $id_redirect
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function redirectDataDelete(int $id_redirect)
    {

        if($id_redirect != '' or $id_redirect != NULL or $id_redirect != 0) {
            /*$entityManager = $this->getDoctrine()->getManager();
            $redirect_delete = $entityManager->getRepository(RedirectData::class)->find($id_redirect);
            $redirect_delete->setDeleted(1);

            $entityManager->persist($redirect_delete);
            $entityManager->flush();*/

            return new JsonResponse(['Success' => true,'info' => 'Success delete redirect in api']);
        } else {
            return new JsonResponse(['Success' => false, 'info' => 'No id redirect']);
        }
    }

    /**
     * @Route("/redirect_data_update/{id_redirect}", name="redirect_data_update")
     * @param Request $request
     * @param int   $id_redirect
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function redirectDataUpdate(Request $request,int $id_redirect)
    {
        //$content = $request->getContent();
        $content = $request->request->all();
        //file_put_contents('redirectDataUpdate.txt', var_export($content, true));
        if (empty($content) or $content == NULL) {
            return new JsonResponse(['Success' => false, 'info' => new BadRequestHttpException()]);
        }

        if($id_redirect != 0 or $id_redirect != '') {
            $entityManager = $this->getDoctrine()->getManager();
            $redirect_update = $entityManager->getRepository(RedirectData::class)->find($id_redirect);
            $redirect_update->setName($content['name']);
            $redirect_update->setLp($content['lp']);
            $redirect_update->setUrl($content['url']);
            $redirect_update->setUsingSmart($content['using_smart']);

            $entityManager->persist($redirect_update);
            $entityManager->flush();

            return new JsonResponse(['Success' => true,'info' => 'Success update redirect in api']);
        } else {
            return new JsonResponse(['Success' => false, 'info' => 'No id redirect']);
        }
    }
}