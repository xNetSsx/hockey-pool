<?php

declare(strict_types=1);

namespace App\Controller\Match;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\PredictionRepository;
use App\Service\Builder\MatchBreakdownBuilder;
use App\Service\Provider\ActiveTournamentProvider;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MatchController extends AbstractController
{
    #[Route('/matches', name: 'match_list')]
    public function list(
        ActiveTournamentProvider $activeTournamentProvider,
        GameRepository $gameRepository,
        PredictionRepository $predictionRepository,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->render('match/empty.html.twig');
        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('match/list.html.twig', [
            'tournament' => $tournament,
            'groupedGames' => $gameRepository->findByTournamentGroupedByPhase($tournament),
            'userPredictions' => $predictionRepository->findByUserIndexedByGame($user, $tournament),
        ]);
    }

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
