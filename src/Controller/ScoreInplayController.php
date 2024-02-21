<?php

namespace App\Controller;

use App\Services\CallApiService;
use App\Services\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Dotenv\Dotenv;



class ScoreInplayController extends AbstractController
{
    private $apiService;
    private $statsService;

    public function __construct(CallApiService $apiService, StatsService $statsService)
    {
        $this->apiService = $apiService;
        $this->statsService = $statsService;
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
        $this->statsService->processMatchResults($this->apiService ,$data);
        return $this->json($data);
    }


}
