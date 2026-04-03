<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\PointEntryRepository;
use App\Repository\TournamentParticipantRepository;
use App\Repository\UserRepository;

/**
 * Builds the tournament leaderboard with ranking.
 * Only includes tournament participants.
 *
 * Tiebreakers (when points are equal):
 *  1. More exact score predictions wins
 *  2. More correct winner predictions wins
 */
final readonly class LeaderboardBuilder
{
    public function __construct(
        private UserRepository $userRepository,
        private PointEntryRepository $pointEntryRepository,
        private TournamentParticipantRepository $participantRepository,
    ) {
    }

    /**
     * Builds leaderboards for multiple tournaments in batch (3 DB queries total).
     *
     * @param list<Tournament> $tournaments
     * @return array<int, list<array{user: User, totalPoints: float, rank: int, exactScores: int, correctWinners: int}>>
     */
    public function buildForTournaments(array $tournaments): array
    {
        if ([] === $tournaments) {
            return [];
        }

        $pointsByTournament = $this->pointEntryRepository->getPointsGroupedByUserForTournaments($tournaments);
        $participantsByTournament = $this->participantRepository->getParticipantUserIdsByTournaments($tournaments);
        $tiebreaksByTournament = $this->pointEntryRepository->getTiebreakCountsByUserForTournaments($tournaments);

        $allUserIds = [];
        foreach ($pointsByTournament as $rows) {
            foreach ($rows as $row) {
                $allUserIds[] = $row['userId'];
            }
        }

        $allUserIds = array_values(array_unique($allUserIds));
        $users = $this->userRepository->findByIds($allUserIds);

        /** @var array<int, list<array{user: User, totalPoints: float, rank: int, exactScores: int, correctWinners: int}>> $result */
        $result = [];

        foreach ($tournaments as $tournament) {
            $tournamentId = (int) $tournament->getId();
            $rows = $pointsByTournament[$tournamentId] ?? [];
            $participantUserIds = $participantsByTournament[$tournamentId] ?? [];
            $tiebreaks = $tiebreaksByTournament[$tournamentId] ?? [];

            $entries = [];
            foreach ($rows as $row) {
                if (!isset($users[$row['userId']])) {
                    continue;
                }

                if ([] !== $participantUserIds && !in_array($row['userId'], $participantUserIds, true)) {
                    continue;
                }

                $tb = $tiebreaks[$row['userId']] ?? ['exactScores' => 0, 'correctWinners' => 0];
                $entries[] = [
                    'user' => $users[$row['userId']],
                    'totalPoints' => $row['totalPoints'],
                    'exactScores' => $tb['exactScores'],
                    'correctWinners' => $tb['correctWinners'],
                    'rank' => 0,
                ];
            }

            usort($entries, static function (array $a, array $b): int {
                $pointsDiff = $b['totalPoints'] <=> $a['totalPoints'];
                if (0 !== $pointsDiff) {
                    return $pointsDiff;
                }

                $exactDiff = $b['exactScores'] <=> $a['exactScores'];
                if (0 !== $exactDiff) {
                    return $exactDiff;
                }

                return $b['correctWinners'] <=> $a['correctWinners'];
            });

            $rank = 0;
            $lastKey = null;

            foreach ($entries as $i => &$entry) {
                $currentKey = $entry['totalPoints'] . '|' . $entry['exactScores'] . '|' . $entry['correctWinners'];

                if ($currentKey !== $lastKey) {
                    $rank = $i + 1;
                    $lastKey = $currentKey;
                }

                $entry['rank'] = $rank;
            }

            unset($entry);

            $result[$tournamentId] = $entries;
        }

        return $result;
    }

    /**
     * @return list<array{user: User, totalPoints: float, rank: int, exactScores: int, correctWinners: int}>
     */
    public function build(Tournament $tournament): array
    {
        $rows = $this->pointEntryRepository->getPointsGroupedByUser($tournament);
        $participantUserIds = $this->participantRepository->getParticipantUserIds($tournament);
        $tiebreaks = $this->pointEntryRepository->getTiebreakCountsByUser($tournament);

        $userIds = array_column($rows, 'userId');
        $users = $this->userRepository->findByIds($userIds);

        $entries = [];

        foreach ($rows as $row) {
            if (!isset($users[$row['userId']])) {
                continue;
            }

            if ([] !== $participantUserIds && !in_array($row['userId'], $participantUserIds, true)) {
                continue;
            }

            $tb = $tiebreaks[$row['userId']] ?? ['exactScores' => 0, 'correctWinners' => 0];
            $entries[] = [
                'user' => $users[$row['userId']],
                'totalPoints' => $row['totalPoints'],
                'exactScores' => $tb['exactScores'],
                'correctWinners' => $tb['correctWinners'],
                'rank' => 0,
            ];
        }

        usort($entries, static function (array $a, array $b): int {
            $pointsDiff = $b['totalPoints'] <=> $a['totalPoints'];
            if (0 !== $pointsDiff) {
                return $pointsDiff;
            }

            $exactDiff = $b['exactScores'] <=> $a['exactScores'];
            if (0 !== $exactDiff) {
                return $exactDiff;
            }

            return $b['correctWinners'] <=> $a['correctWinners'];
        });

        $rank = 0;
        $lastKey = null;

        foreach ($entries as $i => &$entry) {
            $currentKey = $entry['totalPoints'] . '|' . $entry['exactScores'] . '|' . $entry['correctWinners'];

            if ($currentKey !== $lastKey) {
                $rank = $i + 1;
                $lastKey = $currentKey;
            }

            $entry['rank'] = $rank;
        }

        unset($entry);

        return $entries;
    }
}
