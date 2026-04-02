<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Game;
use App\Entity\User;
use App\Enum\TournamentStatus;
use App\Repository\GameRepository;
use App\Repository\PredictionRepository;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Computes prediction status (missing count, next match) for a user.
 * Implements ResetInterface so Symfony clears the per-request cache in worker mode (FrankenPHP).
 */
final class PredictionStatusProvider implements ResetInterface
{
    /**
     * @var array<int, array{missingCount: int, nextMatch: Game|null, missingMatches: list<Game>}>
     */
    private array $cache = [];

    public function __construct(
        private readonly GameRepository $gameRepository,
        private readonly PredictionRepository $predictionRepository,
        private readonly ActiveTournamentProvider $activeTournamentProvider,
    ) {
    }

    public function reset(): void
    {
        $this->cache = [];
    }

    /**
     * @return array{missingCount: int, nextMatch: Game|null, missingMatches: list<Game>}
     */
    public function getStatus(User $user): array
    {
        $userId = (int) $user->getId();

        if (isset($this->cache[$userId])) {
            return $this->cache[$userId];
        }

        $tournament = $this->activeTournamentProvider->getActiveTournament();

        if (null === $tournament || $tournament->getStatus() !== TournamentStatus::InProgress) {
            return $this->cache[$userId] = ['missingCount' => 0, 'nextMatch' => null, 'missingMatches' => []];
        }

        $upcomingGames = $this->gameRepository->findUpcoming($tournament, 100);
        $userPredictions = $this->predictionRepository->findByUserIndexedByGame($user, $tournament);

        $missingMatches = [];

        foreach ($upcomingGames as $game) {
            if (!isset($userPredictions[(int) $game->getId()])) {
                $missingMatches[] = $game;
            }
        }

        return $this->cache[$userId] = [
            'missingCount' => count($missingMatches),
            'nextMatch' => $upcomingGames[0] ?? null,
            'missingMatches' => $missingMatches,
        ];
    }
}
