<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Tournament;
use App\Repository\PointEntryRepository;

/**
 * Builds per-user cumulative points timeline for chart rendering.
 */
final readonly class PointsTimelineBuilder
{
    public function __construct(
        private PointEntryRepository $pointEntryRepository,
    ) {
    }

    /**
     * @return array{labels: list<string>, datasets: list<array{username: string, data: list<float>}>}
     */
    public function build(Tournament $tournament): array
    {
        $rawData = $this->pointEntryRepository->getTimelineData($tournament);

        $matchOrder = [];
        $matchLabels = [];
        $userPoints = [];

        foreach ($rawData as $row) {
            $gameId = $row['gameId'];

            if (!isset($matchOrder[$gameId])) {
                $matchOrder[$gameId] = true;
                $matchLabels[$gameId] = $row['homeCode'] . '-' . $row['awayCode'];
            }

            $userPoints[$row['userId']]['username'] = $row['username'];
            $userPoints[$row['userId']]['games'][$gameId] = $row['points'];
        }

        $gameIds = array_keys($matchOrder);
        $labels = array_values($matchLabels);

        $datasets = [];

        foreach ($userPoints as $userData) {
            $cumulative = 0.0;
            $data = [];

            foreach ($gameIds as $gameId) {
                $cumulative += $userData['games'][$gameId] ?? 0.0;
                $data[] = round($cumulative, 2);
            }

            $datasets[] = [
                'username' => $userData['username'],
                'data' => $data,
            ];
        }

        usort($datasets, static function (array $a, array $b): int {
            $aLast = $a['data'][count($a['data']) - 1] ?? 0;
            $bLast = $b['data'][count($b['data']) - 1] ?? 0;

            return $bLast <=> $aLast;
        });

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
}
