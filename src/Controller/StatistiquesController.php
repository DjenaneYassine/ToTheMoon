<?php

namespace App\Controller;

use App\Repository\StatsRepository;
use App\Services\CallApiService;
use App\Services\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{

    private $apiService;
    private $statService;

    public function __construct(CallApiService $apiService, StatsService $statService)
    {
        $this->apiService = $apiService;
        $this->statService = $statService;
    }


    #[Route('/statistiques', name: 'app_statistiques')]
    public function index(): Response
    {
        $this->statService->processMatchResults($this->apiService, $this->apiService->getDataInplay());
        $match = $this->statService->getDataForDateCurrent();

        return $this->render('statistiques/index.html.twig', [
            'all_match' => $match,
        ]);
    }

    #[Route('/reloadData', name:'reload')]
    public function reloadData(): JsonResponse
    {
        $msgReloadData = "";

        if($this->statService->newSetAll()){
            $msgReloadData = "Les données ont bien été actualisé";
        }else{
            $msgReloadData = "Toutes les données ont déja étais actualisé";
        };
        return new JsonResponse(
            ['message' => $msgReloadData]
        );
    }

    #[Route('/chartStart', name:'chartStart')] // Donnée par défault des graphiques lorsque la page est charger
    public function chartStart(): JsonResponse
    {
        // --- Chart Line --- //
        $date = $this->statService->getDate7LastDay();
        $nbMatchForDate = $this->statService->getNbForDates($this->statService->getDate7LastDay());
        $nbWinForDate = $this->statService->getNbWinForDates($this->statService->getDate7LastDay());
        // --- Chart Line --- //
        
        // --- Chart Donut --- //
        $win = $this->statService->getNbWinForDate($this->statService->getDateYesterday());
        $loose = $this->statService->getNbLooseForDate($this->statService->getDateYesterday());
        // --- Chart Donut --- //

        return new JsonResponse(
            [
                // --- Chart Line --- //
                'date' => $date,
                'nbMatchForDate' => $nbMatchForDate,
                'nbMatchWinForDate' => $nbWinForDate,
                // --- Chart Line --- //

                // --- Chart Donut --- //
                'dataDonut' => [$win, $loose]
                // --- Chart Donut --- //
            ]
        );
    }

    #[Route('/chartline', name:'chartline')]
    public function chartline(): JsonResponse
    {
        $msg = $_GET['messageChartLine'];

        switch ($msg) {
            case "7":
                $date = $this->statService->getDate7LastDay();
                $nbMatchForDate = $this->statService->getNbForDates($this->statService->getDate7LastDay());
                $nbWinForDate = $this->statService->getNbWinForDates($this->statService->getDate7LastDay());
                break;
            case "14":
                $date = $this->statService->getDate14LastDay();
                $nbMatchForDate = $this->statService->getNbForDates($this->statService->getDate14LastDay());
                $nbWinForDate = $this->statService->getNbWinForDates($this->statService->getDate14LastDay());
                break;
            /* case "30":
                $date = $this->statService->getDate30LastDay();
                $nbMatchForDate = $this->statService->getNbForDates($this->statService->getDate30LastDay());
                $nbWinForDate = $this->statService->getNbWinForDates($this->statService->getDate30LastDay());
                break;
            case "90":
                $date = $this->statService->getDate90LastDay();
                $nbMatchForDate = $this->statService->getNbForDates($this->statService->getDate90LastDay());
                $nbWinForDate = $this->statService->getNbWinForDates($this->statService->getDate90LastDay());
                break; */
            default:
        }

        return new JsonResponse(
            [
                'date' => $date,
                'nbMatchForDate' => $nbMatchForDate,
                'nbMatchWinForDate' => $nbWinForDate
            ]
        );
    }

    #[Route('/chartdonut', name:'chartdonut')]
    public function chartdonut(): JsonResponse
    {
        $msg = $_GET['message'];
        switch ($msg) {
            case "Today":
                $win = $this->statService->getNbWinForDate($this->statService->getDateCurrent());
                $loose = $this->statService->getNbLooseForDate($this->statService->getDateCurrent());
                break;
            case "Yesterday":
                $win = $this->statService->getNbWinForDate($this->statService->getDateYesterday());
                $loose = $this->statService->getNbLooseForDate($this->statService->getDateYesterday());
                break;
            case "7":
                $win = $this->statService->getNbWinForDatesDonnut($this->statService->getDate7LastDay());
                $loose = $this->statService->getNbLooseForDatesDonnut($this->statService->getDate7LastDay());
                break;
            case "14":
                $win = $this->statService->getNbWinForDatesDonnut($this->statService->getDate14LastDay());
                $loose = $this->statService->getNbLooseForDatesDonnut($this->statService->getDate14LastDay());
                break;
            case "30":
                $win = $this->statService->getNbWinForDatesDonnut($this->statService->getDate30LastDay());
                $loose = $this->statService->getNbLooseForDatesDonnut($this->statService->getDate30LastDay());
                break;
            default:
        }
        
        return new JsonResponse(
            [
                'data' => [$win, $loose],
            ]
        );
    }
    

    

}
