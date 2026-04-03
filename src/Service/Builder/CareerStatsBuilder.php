<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\TournamentStatus;
use App\Repository\PointEntryRepository;
use App\Repository\PredictionRepository;
use App\Repository\TournamentRepository;

/**
 * Builds career statistics across all finished tournaments.
 *
 * Per-tournament ranking is delegated to LeaderboardBuilder.
 */
final readonly class CareerStatsBuilder
{
    public function __construct(
        private TournamentRepository $tournamentRepository,
        private PointEntryRepository $pointEntryRepository,
        private LeaderboardBuilder $leaderboardBuilder,
        private PredictionRepository $predictionRepository,
    ) {
    }

    /**
     * Career stats for a single user across all finished tournaments.
     *
     * @return array{
     *     tournamentsPlayed: int,
     *     firstPlace: int,
     *     secondPlace: int,
     *     thirdPlace: int,
     *     podiumFinishes: int,
     *     averageRank: float|null,
     *     bestRank: int|null,
     *     totalPredictions: int,
     *     careerExactScores: int,
     *     careerCorrectWinners: int,
     *     careerWrongPredictions: int,
     *     careerAccuracy: float|null,
     *     careerExactRate: float|null,
     *     bestTournamentAccuracy: float|null,
     * }
     */
    public function buildForUser(User $user): array
    {
        $finishedTournaments = $this->tournamentRepository->findBy(
            ['status' => TournamentStatus::Finished],
            ['year' => 'ASC'],
        );

        $leaderboardsByTournament = $this->leaderboardBuilder->buildForTournaments($finishedTournaments);
        $accuracyByTournament = $this->predictionRepository->getAccuracyStatsByTournamentForUser($user, $finishedTournaments);

        $tournamentsPlayed = 0;
        $firstPlace = 0;
        $secondPlace = 0;
        $thirdPlace = 0;
        $rankSum = 0;
        $bestRank = null;

        $totalPredictions = 0;
        $careerExactScores = 0;
        $careerCorrectWinners = 0;
        $careerWrongPredictions = 0;
        $bestTournamentAccuracy = null;

        $userId = $user->getId();

        foreach ($finishedTournaments as $tournament) {
            $tournamentId = (int) $tournament->getId();
            $leaderboard = $leaderboardsByTournament[$tournamentId] ?? [];

            $rank = null;
            foreach ($leaderboard as $row) {
                if ($row['user']->getId() === $userId) {
                    $rank = $row['rank'];
                    break;
                }
            }

            if (null === $rank) {
                continue;
            }

            $tournamentsPlayed++;
            $rankSum += $rank;

            if (null === $bestRank || $rank < $bestRank) {
                $bestRank = $rank;
            }

            if (1 === $rank) {
                $firstPlace++;
            } elseif (2 === $rank) {
                $secondPlace++;
            } elseif (3 === $rank) {
                $thirdPlace++;
            }

            $acc = $accuracyByTournament[$tournamentId] ?? null;
            if (null !== $acc) {
                $totalPredictions += $acc['total'];
                $careerExactScores += $acc['exactScores'];
                $careerCorrectWinners += $acc['correctWinners'];
                $careerWrongPredictions += $acc['wrongPredictions'];
                $finishedWithPrediction = $acc['correctWinners'] + $acc['wrongPredictions'];
                $tournamentAccuracy = $finishedWithPrediction > 0
                    ? round($acc['correctWinners'] / $finishedWithPrediction * 100, 1)
                    : 0.0;
                $bestTournamentAccuracy = null === $bestTournamentAccuracy
                    ? $tournamentAccuracy
                    : max($bestTournamentAccuracy, $tournamentAccuracy);
            }
        }

        $finishedWithPrediction = $careerCorrectWinners + $careerWrongPredictions;
        $careerAccuracy = $finishedWithPrediction > 0
            ? (float) round($careerCorrectWinners / $finishedWithPrediction * 100, 1)
            : null;
        $careerExactRate = $finishedWithPrediction > 0
            ? (float) round($careerExactScores / $finishedWithPrediction * 100, 1)
            : null;

        return [
            'tournamentsPlayed' => $tournamentsPlayed,
            'firstPlace' => $firstPlace,
            'secondPlace' => $secondPlace,
            'thirdPlace' => $thirdPlace,
            'podiumFinishes' => $firstPlace + $secondPlace + $thirdPlace,
            'averageRank' => $tournamentsPlayed > 0 ? (float) round($rankSum / $tournamentsPlayed, 2) : null,
            'bestRank' => $bestRank,
            'totalPredictions' => $totalPredictions,
            'careerExactScores' => $careerExactScores,
            'careerCorrectWinners' => $careerCorrectWinners,
            'careerWrongPredictions' => $careerWrongPredictions,
            'careerAccuracy' => $careerAccuracy,
            'careerExactRate' => $careerExactRate,
            'bestTournamentAccuracy' => $bestTournamentAccuracy,
        ];
    }

    /**
     * All-time standings: all users who participated in any finished tournament,
     * sorted by: firstPlace DESC, secondPlace DESC, thirdPlace DESC, averageRank ASC, tournamentsPlayed DESC.
     *
     * @return list<array{
     *     user: User,
     *     tournamentsPlayed: int,
     *     firstPlace: int,
     *     secondPlace: int,
     *     thirdPlace: int,
     *     podiumFinishes: int,
     *     averageRank: float|null,
     *     bestRank: int|null,
     *     rank: int,
     *     careerExactScores: int,
     *     careerCorrectWinners: int,
     *     careerAccuracy: float|null,
     *     careerExactRate: float|null,
     *     finishedPredictions: int,
     * }>
     */
    public function buildAllTimeStandings(): array
    {
        $finishedTournaments = $this->tournamentRepository->findBy(
            ['status' => TournamentStatus::Finished],
            ['year' => 'ASC'],
        );

        if ([] === $finishedTournaments) {
            return [];
        }

        /** @var array<int, array{user: User, tournamentsPlayed: int, firstPlace: int, secondPlace: int, thirdPlace: int, rankSum: float, bestRank: int|null}> $statsById */
        $statsById = [];

        $leaderboardsByTournament = $this->leaderboardBuilder->buildForTournaments($finishedTournaments);

        foreach ($leaderboardsByTournament as $leaderboard) {
            foreach ($leaderboard as $row) {
                $uid = (int) $row['user']->getId();
                $rank = $row['rank'];

                if (!isset($statsById[$uid])) {
                    $statsById[$uid] = [
                        'user' => $row['user'],
                        'tournamentsPlayed' => 0,
                        'firstPlace' => 0,
                        'secondPlace' => 0,
                        'thirdPlace' => 0,
                        'rankSum' => 0.0,
                        'bestRank' => null,
                    ];
                }

                $statsById[$uid]['tournamentsPlayed']++;
                $statsById[$uid]['rankSum'] += (float) $rank;

                if (null === $statsById[$uid]['bestRank'] || $rank < $statsById[$uid]['bestRank']) {
                    $statsById[$uid]['bestRank'] = $rank;
                }

                if (1 === $rank) {
                    $statsById[$uid]['firstPlace']++;
                } elseif (2 === $rank) {
                    $statsById[$uid]['secondPlace']++;
                } elseif (3 === $rank) {
                    $statsById[$uid]['thirdPlace']++;
                }
            }
        }

        if ([] === $statsById) {
            return [];
        }

        $careerTiebreaks = $this->pointEntryRepository->getCareerTiebreakCountsByUsers($finishedTournaments);
        $careerFinishedCounts = $this->predictionRepository->getFinishedPredictionCountsByUsers($finishedTournaments);

        $entries = [];
        foreach ($statsById as $uid => $s) {
            $tb = $careerTiebreaks[$uid] ?? ['exactScores' => 0, 'correctWinners' => 0];
            $finishedCount = $careerFinishedCounts[$uid] ?? 0;
            $careerAccuracy = $finishedCount > 0 ? (float) round($tb['correctWinners'] / $finishedCount * 100, 1) : null;
            $careerExactRate = $finishedCount > 0 ? (float) round($tb['exactScores'] / $finishedCount * 100, 1) : null;

            $tp = $s['tournamentsPlayed'];
            $entries[] = [
                'user' => $s['user'],
                'tournamentsPlayed' => $tp,
                'firstPlace' => $s['firstPlace'],
                'secondPlace' => $s['secondPlace'],
                'thirdPlace' => $s['thirdPlace'],
                'podiumFinishes' => $s['firstPlace'] + $s['secondPlace'] + $s['thirdPlace'],
                'averageRank' => $tp > 0 ? (float) round($s['rankSum'] / $tp, 2) : null,
                'bestRank' => $s['bestRank'],
                'rank' => 0,
                'careerExactScores' => $tb['exactScores'],
                'careerCorrectWinners' => $tb['correctWinners'],
                'careerAccuracy' => $careerAccuracy,
                'careerExactRate' => $careerExactRate,
                'finishedPredictions' => $finishedCount,
            ];
        }

        usort($entries, static function (array $a, array $b): int {
            $diff = $b['firstPlace'] <=> $a['firstPlace'];
            if (0 !== $diff) {
                return $diff;
            }

            $diff = $b['secondPlace'] <=> $a['secondPlace'];
            if (0 !== $diff) {
                return $diff;
            }

            $diff = $b['thirdPlace'] <=> $a['thirdPlace'];
            if (0 !== $diff) {
                return $diff;
            }

            $aAvg = $a['averageRank'] ?? PHP_FLOAT_MAX;
            $bAvg = $b['averageRank'] ?? PHP_FLOAT_MAX;
            $diff = $aAvg <=> $bAvg;
            if (0 !== $diff) {
                return $diff;
            }

            return $b['tournamentsPlayed'] <=> $a['tournamentsPlayed'];
        });

        $rank = 0;
        $lastKey = null;

        foreach ($entries as $i => &$entry) {
            $currentKey = $entry['firstPlace'] . '|' . $entry['secondPlace'] . '|' . $entry['thirdPlace'] . '|' . ($entry['averageRank'] ?? '') . '|' . $entry['tournamentsPlayed'];

            if ($currentKey !== $lastKey) {
                $rank = $i + 1;
                $lastKey = $currentKey;
            }

            $entry['rank'] = $rank;
        }

        unset($entry);

        return $entries;
    }

    /**
     * Per-tournament podium: for each finished tournament return the top-3 finishers.
     *
     * @return list<array{
     *     tournament: Tournament,
     *     podium: list<array{user: User, points: float, rank: int}>,
     * }>
     */
    public function buildTournamentPodiums(): array
    {
        $finishedTournaments = $this->tournamentRepository->findBy(
            ['status' => TournamentStatus::Finished],
            ['year' => 'DESC'],
        );

        $leaderboardsByTournament = $this->leaderboardBuilder->buildForTournaments($finishedTournaments);

        $result = [];

        foreach ($finishedTournaments as $tournament) {
            $tournamentId = (int) $tournament->getId();
            $leaderboard = $leaderboardsByTournament[$tournamentId] ?? [];

            $podium = [];
            foreach ($leaderboard as $row) {
                if ($row['rank'] > 3) {
                    continue;
                }

                $podium[] = [
                    'user' => $row['user'],
                    'points' => $row['totalPoints'],
                    'rank' => $row['rank'],
                ];
            }

            $result[] = [
                'tournament' => $tournament,
                'podium' => $podium,
            ];
        }

        return $result;
    }
}
