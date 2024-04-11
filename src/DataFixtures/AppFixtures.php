<?php

namespace App\DataFixtures;

use App\Entity\Stats;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    private function getRandomPlayer(array $players)
    {
        return $players[array_rand($players)];
    }

    public function load(ObjectManager $manager)
    {
        $players = [
            "Michal Urban",
            "Jan Marek",
            "Petr Novák",
            "Tomáš Kovář",
            "Adam Novotný",
            "Josef Kopecký",
            "Lukáš Novák",
            "Ondřej Pospíšil",
            "Marek Svoboda",
            "Pavel Novák",
            "Martin Marek",
            "Jakub Veselý",
            "Tomáš Štěpánek",
            "Petr Kovář",
            // Ajoutez plus de noms de joueurs ici...
        ];

        // Générer 10 fixtures avec des données aléatoires
        for ($i = 0; $i < 10; $i++) {
            $date = new DateTime('2024-04-07');

            $stat = new Stats;
            $stat->setLeague("Czech Liga Pro");
            $stat->setDate($date);
            $stat->setNameHome($this->getRandomPlayer($players));
            $stat->setNameAway($this->getRandomPlayer($players));
            $stat->setIdMatch(mt_rand(1000000, 9999999));
            $stat->setScore(mt_rand(0, 10) . '-' . mt_rand(0, 10));
            $stat->setScoreSet("2-0" or "0-2");
            $stat->setWin((bool) mt_rand(0, 1));

            $manager->persist($stat);
        }

        $manager->flush();
    }
}
