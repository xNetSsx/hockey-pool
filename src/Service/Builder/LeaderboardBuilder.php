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
     * @return list<array{user: User, totalPoints: float, rank: int}>
     */
    public function build(Tournament $tournament): array
    {
        $rows = $this->pointEntryRepository->getPointsGroupedByUser($tournament);
        $participantUserIds = $this->participantRepository->getParticipantUserIds($tournament);

        $userIds = array_column($rows, 'userId');
        $users = $this->userRepository->findByIds($userIds);

        $leaderboard = [];
        $rank = 0;
        $lastPoints = null;
        $position = 0;

        foreach ($rows as $row) {
            if (!isset($users[$row['userId']])) {
                continue;
            }

            if ([] !== $participantUserIds && !in_array($row['userId'], $participantUserIds, true)) {
                continue;
            }

            $position++;
            $points = $row['totalPoints'];

            if ($points !== $lastPoints) {
                $rank = $position;
                $lastPoints = $points;
            }

            $leaderboard[] = [
                'user' => $users[$row['userId']],
                'totalPoints' => $points,
                'rank' => $rank,
            ];
        }

        return $leaderboard;
    }
}
