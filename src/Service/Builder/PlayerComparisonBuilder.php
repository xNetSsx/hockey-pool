<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\PointEntryRepository;
use App\Repository\PredictionRepository;

/**
 * Builds per-match comparison data for head-to-head user views.
 */
final readonly class PlayerComparisonBuilder
{
    public function __construct(
        private PredictionRepository $predictionRepository,
        private PointEntryRepository $pointEntryRepository,
        private GameRepository $gameRepository,
    ) {
    }

    /**
     * @param list<User> $users
     * @return array{
     *     games: list<Game>,
     *     predictions: array<int, array<int, Prediction|null>>,
     *     matchPoints: array<int, array<int, float>>,
     *     runningTotals: array<int, array<int, float>>,
     * }
     */
    public function build(array $users, Tournament $tournament): array
    {
        $games = $this->gameRepository->findByTournamentGroupedByPhase($tournament);
        $flatGames = array_merge(...array_values($games));

        $allPredictions = [];
        $allMatchPoints = [];

        foreach ($users as $user) {
            $userId = (int) $user->getId();
            $allPredictions[$userId] = $this->predictionRepository->findByUserIndexedByGame($user, $tournament);

            $indexed = [];
            foreach ($this->pointEntryRepository->getPointsPerMatch($user, $tournament) as $row) {
                $indexed[$row['gameId']] = $row['points'];
            }

            $allMatchPoints[$userId] = $indexed;
        }

        $runningTotals = [];
        $cumulative = array_fill_keys(array_map(static fn (User $u) => (int) $u->getId(), $users), 0.0);

        foreach ($flatGames as $game) {
            if (!$game->isFinished()) {
                continue;
            }

            $gameId = (int) $game->getId();

            foreach ($users as $user) {
                $userId = (int) $user->getId();
                $pts = $allMatchPoints[$userId][$gameId] ?? 0.0;
                $cumulative[$userId] += $pts;
                $runningTotals[$userId][$gameId] = $cumulative[$userId];
            }
        }

        return [
            'games' => $flatGames,
            'predictions' => $allPredictions,
            'matchPoints' => $allMatchPoints,
            'runningTotals' => $runningTotals,
        ];
    }
}
