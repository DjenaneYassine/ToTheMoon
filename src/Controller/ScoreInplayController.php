<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ScoreInplayController extends AbstractController
{
    #[Route('/direct', name: 'app_score_inplay')]
    public function index(): Response
    {
        return $this->render('score_inplay/scoreInplayHome.html.twig', [
            'controller_name' => 'ScoreInplayController',
        ]);
    }
}
