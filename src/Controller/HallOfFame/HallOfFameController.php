<?php

declare(strict_types=1);

namespace App\Controller\HallOfFame;

use App\Service\Builder\CareerStatsBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HallOfFameController extends AbstractController
{
    #[Route('/hall-of-fame', name: 'hall_of_fame')]
    public function index(CareerStatsBuilder $careerStatsBuilder): Response
    {
        return $this->render('hall_of_fame/index.html.twig', [
            'standings' => $careerStatsBuilder->buildAllTimeStandings(),
            'podiums' => $careerStatsBuilder->buildTournamentPodiums(),
        ]);
    }
}
