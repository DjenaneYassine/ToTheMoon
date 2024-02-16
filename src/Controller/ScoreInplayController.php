<?php

namespace App\Controller;

use App\Services\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Dotenv\Dotenv;



class ScoreInplayController extends AbstractController
{
    private $apiService;
    private $statsController;

    public function __construct(CallApiService $apiService, StatistiquesController $statsController)
    {
        $this->apiService = $apiService;
        $this->statsController = $statsController;
    }


    #[Route('/direct', name: 'app_score_inplay')]
    public function index(): Response
    {
        return $this->render('score_inplay/scoreInplayHome.html.twig', [
            'datas' => $this->apiService->getDataInplay()
        ]);
    }
    #[Route('/api', name: 'api')]
    public function data(): JsonResponse
    {
        $data = $this->apiService->getDataInplay();
        /* $this->statsController->processMatchResults($data); */
        return $this->json($data);
    }


}
