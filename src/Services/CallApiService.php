<?php

namespace App\Services;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class CallApiService
{
    private $client;
    private $id_sport = 92;

    
    public function getIdSport()
    {
        return $this->id_sport;
    }


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    #[Route('/api', name: 'api')]
    public function getDataInplay(): array // initialise l'api et rajoute le set d'un match courant
    {
        define("HOME", "home");
        define("AWAY", "away");
        $response = $this->client->request(
            'GET',
            'https://api.b365api.com/v3/events/inplay?sport_id='.$this->getIdSport().'&token='.$_ENV['API_KEY']
        );

        $data = $response->toArray();
        foreach ($data["results"] as &$match) {
            $set_home = 0;
            $set_away = 0;

            if (isset($match["scores"])) {

                    foreach ($match["scores"] as $results) {
                        if ($results["home"] == 11 && ($results["home"] - $results["away"]) >= 2) {
                            ++$set_home;
                        } elseif ($results["home"] > 11 && ($results["home"] - $results["away"]) >= 2) {
                            ++$set_home;
                        } elseif ($results["away"] == 11 && ($results["away"] - $results["home"]) >= 2) {
                            ++$set_away;
                        } elseif ($results["away"] > 11 && ($results["away"] - $results["home"]) >= 2) {
                            ++$set_away;
                        }
                    }
                    $scores = $set_home . "-" . $set_away;
                    $match["set"] = $scores;
                }else{
                    $match["set"] = "0-0";
                }

        }
        return $data;
    }

    public function goodLeague($match) : bool //test si la league et Czech Liga Pro ou TT Elite Series
    {
        return (($match["league"]["name"] === "Czech Liga Pro") || ($match["league"]["name"] === "TT Elite Series"))  ? true : false;
    }


}