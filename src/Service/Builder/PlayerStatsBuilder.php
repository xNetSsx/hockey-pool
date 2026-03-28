<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\PointEntryRepository;
use App\Repository\PredictionRepository;

/**
 * Computes per-player accuracy and best/worst day stats.
 */
final readonly class PlayerStatsBuilder
{
    public function __construct(
        private PredictionRepository $predictionRepository,
        private PointEntryRepository $pointEntryRepository,
    ) {
    }

    /**
     * @return array{
     *     correctWinners: int,
     *     exactScores: int,
     *     wrongPredictions: int,
     *     totalPredictions: int,
     *     accuracy: float,
     *     bestMatchDay: array{date: string, points: float}|null,
     *     worstMatchDay: array{date: string, points: float}|null,
     * }
     */
    public function build(User $user, Tournament $tournament): array
    {
        $predictions = $this->predictionRepository->findByUserIndexedByGame($user, $tournament);
        $pointsPerMatch = $this->indexPointsByGameId(
            $this->pointEntryRepository->getPointsPerMatch($user, $tournament),
        );

        $correctWinners = 0;
        $exactScores = 0;
        $wrongPredictions = 0;
        $finishedWithPrediction = 0;

        /** @var array<string, float> $dayPoints */
        $dayPoints = [];

        foreach ($predictions as $gameId => $prediction) {
            $game = $prediction->getGame();

            if (!$game->isFinished()) {
                continue;
            }

            $finishedWithPrediction++;
            $matchPoints = $pointsPerMatch[$gameId] ?? 0.0;
            $dateKey = $game->getPlayedAt()->format('Y-m-d');
            $dayPoints[$dateKey] = ($dayPoints[$dateKey] ?? 0.0) + $matchPoints;

            if ($prediction->isExactScore($game)) {
                $exactScores++;
                $correctWinners++;
            } elseif ($prediction->isCorrectWinner($game)) {
                $correctWinners++;
            } else {
                $wrongPredictions++;
            }
        }

        $bestDay = null;
        $worstDay = null;

        if (count($dayPoints) > 0) {
            arsort($dayPoints);
            $bestDate = array_key_first($dayPoints);
            $bestDay = ['date' => $bestDate, 'points' => $dayPoints[$bestDate]];

            asort($dayPoints);
            $worstDate = array_key_first($dayPoints);
            $worstDay = ['date' => $worstDate, 'points' => $dayPoints[$worstDate]];
        }

        return [
            'correctWinners' => $correctWinners,
            'exactScores' => $exactScores,
            'wrongPredictions' => $wrongPredictions,
            'totalPredictions' => count($predictions),
            'accuracy' => $finishedWithPrediction > 0 ? round($correctWinners / $finishedWithPrediction * 100, 1) : 0.0,
            'bestMatchDay' => $bestDay,
            'worstMatchDay' => $worstDay,
        ];
    }

    /**
     * @param list<array{gameId: int, points: float}> $rows
     * @return array<int, float>
     */
    private function indexPointsByGameId(array $rows): array
    {
        $indexed = [];
        foreach ($rows as $row) {
            $indexed[$row['gameId']] = $row['points'];
        }

        return $indexed;
    }
}
