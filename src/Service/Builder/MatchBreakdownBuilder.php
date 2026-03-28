<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Game;
use App\Entity\PointEntry;
use App\Entity\Prediction;
use App\Entity\User;
use App\Repository\PointEntryRepository;
use App\Repository\PredictionRepository;

/**
 * Builds per-user point breakdown for a single match.
 */
final readonly class MatchBreakdownBuilder
{
    public function __construct(
        private PointEntryRepository $pointEntryRepository,
        private PredictionRepository $predictionRepository,
    ) {
    }

    /**
     * @return list<array{
     *     user: User,
     *     prediction: Prediction,
     *     correctWinner: bool,
     *     exactScore: bool,
     *     basePoints: float,
     *     opponentBonus: float,
     *     exactBonus: float,
     *     totalPoints: float,
     * }>
     */
    public function build(Game $game): array
    {
        $predictions = $this->predictionRepository->findByGame($game);
        $pointEntries = $this->pointEntryRepository->findByGame($game);

        /** @var array<int, list<PointEntry>> $entriesByUser */
        $entriesByUser = [];
        foreach ($pointEntries as $entry) {
            $entriesByUser[$entry->getUser()->getId()][] = $entry;
        }

        $breakdown = [];

        foreach ($predictions as $prediction) {
            $userId = $prediction->getUser()->getId();
            $entries = $entriesByUser[$userId] ?? [];

            $basePoints = 0.0;
            $opponentBonus = 0.0;
            $exactBonus = 0.0;

            foreach ($entries as $entry) {
                $reason = $entry->getReason();
                if ('Correct winner' === $reason) {
                    $basePoints = $entry->getPoints();
                } elseif (str_starts_with($reason, 'Wrong opponent bonus')) {
                    $opponentBonus = $entry->getPoints();
                } elseif ('Exact score bonus' === $reason) {
                    $exactBonus = $entry->getPoints();
                }
            }

            $breakdown[] = [
                'user' => $prediction->getUser(),
                'prediction' => $prediction,
                'correctWinner' => $game->isFinished() && $prediction->isCorrectWinner($game),
                'exactScore' => $game->isFinished() && $prediction->isExactScore($game),
                'basePoints' => $basePoints,
                'opponentBonus' => $opponentBonus,
                'exactBonus' => $exactBonus,
                'totalPoints' => $basePoints + $opponentBonus + $exactBonus,
            ];
        }

        usort($breakdown, static fn (array $a, array $b) => $b['totalPoints'] <=> $a['totalPoints']);

        return $breakdown;
    }
}
