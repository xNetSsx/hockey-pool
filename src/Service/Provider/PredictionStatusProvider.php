<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Game;
use App\Entity\User;
use App\Enum\TournamentStatus;
use App\Repository\GameRepository;
use App\Repository\PredictionRepository;

/**
 * Computes prediction status (missing count, next match) for a user.
 */
final readonly class PredictionStatusProvider
{
    public function __construct(
        private GameRepository $gameRepository,
        private PredictionRepository $predictionRepository,
        private ActiveTournamentProvider $activeTournamentProvider,
    ) {
    }

    /**
     * @return array{missingCount: int, nextMatch: Game|null, missingMatches: list<Game>}
     */
    public function getStatus(User $user): array
    {
        $tournament = $this->activeTournamentProvider->getActiveTournament();

        if (null === $tournament || $tournament->getStatus() !== TournamentStatus::InProgress) {
            return ['missingCount' => 0, 'nextMatch' => null, 'missingMatches' => []];
        }

        $upcomingGames = $this->gameRepository->findUpcoming($tournament, 100);
        $userPredictions = $this->predictionRepository->findByUserIndexedByGame($user, $tournament);

        $missingMatches = [];

        foreach ($upcomingGames as $game) {
            if (!isset($userPredictions[$game->getId()])) {
                $missingMatches[] = $game;
            }
        }

        return [
            'missingCount' => count($missingMatches),
            'nextMatch' => $upcomingGames[0] ?? null,
            'missingMatches' => $missingMatches,
        ];
    }
}
