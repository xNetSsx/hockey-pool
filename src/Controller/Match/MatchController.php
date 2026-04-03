<?php

declare(strict_types=1);

namespace App\Controller\Match;

use App\Entity\Game;
use App\Service\Builder\MatchBreakdownBuilder;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MatchController extends AbstractController
{
    #[Route('/matches/{id}', name: 'match_detail', requirements: ['id' => '\d+'])]
    public function detail(
        Game $game,
        MatchBreakdownBuilder $matchBreakdownBuilder,
    ): Response {
        $matchStarted = $game->getPlayedAt() <= new DateTimeImmutable();

        return $this->render('match/detail.html.twig', [
            'game' => $game,
            'breakdown' => $matchStarted ? $matchBreakdownBuilder->build($game) : [],
            'matchStarted' => $matchStarted,
        ]);
    }
}
