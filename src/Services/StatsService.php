<?php

namespace App\Services;
use App\Repository\MailHistoryRepository;
use App\Repository\StatsRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Services\SendMailerService;


class StatsService
{
    private $client;
    private $statsrepository;


    public function __construct(
        HttpClientInterface $client, 
        StatsRepository $statsrepository, 
        private readonly SendMailerService $sendMailerService,
        private readonly MailHistoryRepository $mailHistoryRepository
        )
    {
        $this->client = $client;
        $this->statsrepository = $statsrepository;
    }

    public function getAllIdMatch() 
    {
        return $this->statsrepository->findIdMatch(); // récupère tous les matchs 
    }

    public function getSS($id){
        return $this->statsrepository->findSS($id);
    }

    private function callApiEvent(int $id): array
    {
        $id = (int)$id;
        $response = $this->client->request(
            'GET',
            'https://api.b365api.com/v1/event/view?token='.$_ENV["API_KEY"].'&event_id='.$id
        );
        $data = $response->toArray();
        if($data["results"][0]["time_status"] != 3){
            return [];
        }
        return $data;
    }

    public function getAllsets(): array
    {
        $data = $this->getAllIdMatch();
        $tab = [];

        if(!empty($data)){
            foreach ($data as $val){
                $set = $this->callApiEvent($val["idMatch"]);
                $score = $this->getSS($val["idMatch"]);
                $tab[$val["idMatch"]] = array(
                    "score" => $score,
                    "set" => $set["results"][0]["ss"]
                );
                
            }
        }
        return $tab;
    }

    public function newSetAll(): bool
    {
        $data = $this->getAllsets();
        if(!empty($data)){
            foreach($data as $id =>$match){
                if(!empty($this->statsrepository->newSet($match["set"], $id)))
                {
                    $this->statsrepository->newSet($match["set"], $id);
                    if($this->conditionTestPassedFinal($match)){
                        $this->statsrepository->valueWin($id, true);
                    }else{
                        $this->statsrepository->valueWin($id, false);
                    }
                }
            }
        }else{
            return false;
        }
        return true;
    }

    public function conditionTestPassedFinal($tab) : bool
    {
        $score = explode("-", $tab["score"]);
        $set = explode("-", $tab["set"]);
        $scoreHome = $score[0];
        $scoreAway = $score[1];
        $setHome= $set[0];
        $setAway = $set[1];

        if(($scoreHome > $scoreAway) && $setHome == 3 && $setAway == 0){
            return true;
        }elseif(($scoreAway > $scoreHome) && $setAway == 3 && $setHome == 0){
            return true;
        }

        return false;
    }

    private function TwoSetAdvencedAndScore($match, $scoreEcart) : bool
    {
        if(isset($match)){
            $set = explode("-", $match["set"]);
            $ss = explode("-", $match["ss"]);
            $setHome = $set[0];
            $ssHome = $ss[0];
            $setAway = $set[1];
            $ssAway = $ss[1];
            if($this->conditionTestPassed($setHome, $ssHome, $setAway, $ssAway, $scoreEcart)){
                return true;
            }elseif($this->conditionTestPassed($setAway, $ssAway, $setHome, $ssHome, $scoreEcart)){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    private function conditionTestPassed($set, $ss, $setAdversaire, $ssAdversaire, $scoreEcart) : bool
    {
        $test = false;
        if (($set === "2" && $setAdversaire === "0") && ($ss - $ssAdversaire == $scoreEcart) && ($ss != "11" && $ss != "10" && $ss != "9")) {
            $test = true;
        }
        return $test;
    }
    
    public function sendMail($match)
    {
        $this->sendMailerService->sendEmail($match["home"]["name"], $match["away"]["name"], $match["ss"], $match["league"]["name"]);
    }

    public function processMatchResults(CallApiService $callApiService, $data) : void
    {

        foreach ($data["results"] as $match){
            if($this->TwoSetAdvencedAndScore($match, "4") && $callApiService->goodLeague($match)) {
                $this->statsrepository->newsStats($match);
            }elseif($this->TwoSetAdvencedAndScore($match, "2") && $callApiService->goodLeague($match)) {
                if($this->mailHistoryRepository->idUnique($match)){
                    $this->sendMail($match);
                    $this->mailHistoryRepository->newMail($match);
                }
        }}
    }

    
    public function getDataForDateCurrent(): array // retourne un tableau de toutes les données de la date d'aujourd'hui
    {
        return $this->statsrepository->getDataForDays($this->getDateCurrent()); 
    }

    public function getDate7LastDay(): array // retourne un tableau avec les dates 7 derniers jours
    {
        $dates = [];

        $endDate = new \DateTime();

        $startDate = (new \DateTime())->modify('-7 days');

        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $currentDate->modify('+1 day');
            $dates[] = $currentDate->format('d-m');
            
        }

        return $dates;
    }

    public function getDate14LastDay(): array // retourne un tableau avec les dates 14 derniers jours
    {
        $dates = [];

        $endDate = new \DateTime();

        $startDate = (new \DateTime())->modify('-14 days');

        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $currentDate->modify('+1 day');
            $dates[] = $currentDate->format('d-m');
            
        }

        return $dates;
    }

    public function getDate30LastDay(): array // retourne un tableau avec les dates 30 derniers jours
    {
        $dates = [];

        $endDate = new \DateTime();

        $startDate = (new \DateTime())->modify('-30 days');

        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $currentDate->modify('+1 day');
            $dates[] = $currentDate->format('d-m');
            
        }

        return $dates;
    }

    public function getDate90LastDay(): array // retourne un tableau avec les dates 90 derniers jours
    {
        $dates = [];

        $endDate = new \DateTime();

        $startDate = (new \DateTime())->modify('-14 days');

        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $currentDate->modify('+1 day');
            $dates[] = $currentDate->format('d-m');
            
        }

        return $dates;
    }

    public function getDateCurrent(): string // retourne une chaine de caractère avec la date d'hier
    {
        $dayCurrent = new \DateTime();
        $dayCurrent = $dayCurrent->format('d-m');

        return $dayCurrent;
    }

    public function getDateYesterday(): string // retourne une chaine de caractère avec la date d'aujourd'hui
    {
        $dayCurrent = new \DateTime();
        $dayCurrent->modify('-1 day');
        $dayYesterday = $dayCurrent->format('d-m');

        return $dayYesterday;
    }

    public function getNbForDates($dates): array // retourne le nombre de match par date
    {
        $nbMatchForDate = [];
        foreach($dates as $date){
            $nbMatchForDate[] = $this->statsrepository->getNbDataForDays($date);
        }

        return $nbMatchForDate;
    }

    public function getNbWinForDates($dates): array // retourne le nombre de victoire par date
    {
        $nbMatchsWinForDates = [];
        foreach($dates as $date){
            $nbMatchsWinForDates[] = $this->statsrepository->getWinForDays($date);
        }

        return $nbMatchsWinForDates;
    }

    public function getNbWinForDatesDonnut($dates): int // retourne le nombre de victoire pour un intervalle de date
    {
        $nbMatchsWinForDates = 0;
        foreach($dates as $date){
            $nbMatchsWinForDates += $this->statsrepository->getWinForDays($date);
        }

        return $nbMatchsWinForDates;
    }

    public function getNbLooseForDatesDonnut($dates): int //  retourne le nombre de défaite pour un intervalle de date
    {
        $nbMatchsLooseForDates = 0;
        foreach($dates as $date){
            $nbMatchsLooseForDates += $this->statsrepository->getLooseForDays($date);
        }

        return $nbMatchsLooseForDates;
    }

    public function getNbWinForDate($date): int // retourne un entier avec le nombre de victoire par date indiquer
    {
        $nbMatchWinForDate = $this->statsrepository->getWinForDays($date);
        
        return $nbMatchWinForDate;
    } 

    public function getNbLooseForDate($date): int// retourne un entier avec le nombre de défaite par date indiquer
    {
        $nbMatchLooseForDate = $this->statsrepository->getLooseForDays($date);
        return $nbMatchLooseForDate;
    } 
    

    
}

