<?php

namespace ICS\QwantBundle\Controller;

use ICS\QwantBundle\Service\QwantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/" , name="ics_qwant_homepage")
     */
    public function index(Request $request, QwantService $searchService)
    {
        $search = $request->get('search');
        return $this->render('@Qwant/index.html.twig', [
            'search' => $search,
        ]);
    }

    /**
     * @Route("/search/{type}/{search}/{offset}" , name="ics_qwant_next_homepage")
     */
    public function search(QwantService $searchService, $search = '', $offset = 0, $type = 'web')
    {
        $response = [];

        if ('' != $search) {
            $res = $searchService->search($search, 30, $offset, $type);
            $response = $res->data->result->items;
        }

        switch ($type) {
            case 'images':
                $result['results'] = $this->renderView('@Qwant/imageResults.html.twig', [
                    'response' => $response,
                ]);
            break;
            case 'videos':
                $result['results'] = $this->renderView('@Qwant/videosResults.html.twig', [
                    'response' => $response,
                ]);
            break;
            case 'news':
                $result['results'] = $this->renderView('@Qwant/newsResults.html.twig', [
                    'response' => $response,
                ]);
            break;
            default:
                $result['results'] = $this->renderView('@Qwant/webResults.html.twig', [
                    'response' => $response,
                ]);
        }

        $result['next_offset'] = count($response);

        return new JsonResponse($result);
    }

    public function getVideos()
    {
        $videoId = 'ok';
        $url = 'https://www.youtube.com/get_video_info?video_id='.$videoId.'&el=embedded&ps=default&eurl=&gl=US&hl=en';
    }
}
