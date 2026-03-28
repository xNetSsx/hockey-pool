<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\PointEntryRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Builds the tournament leaderboard with ranking.
 */
final readonly class LeaderboardBuilder
{
    public function __construct(
        private EntityManagerInterface $em,
        private PointEntryRepository $pointEntryRepository,
    ) {
    }

    /**
     * @return list<array{user: User, totalPoints: float, rank: int}>
     */
    public function build(Tournament $tournament): array
    {
        $rows = $this->pointEntryRepository->getPointsGroupedByUser($tournament);

        $leaderboard = [];
        $rank = 0;
        $lastPoints = null;
        $position = 0;

        foreach ($rows as $row) {
            $position++;
            $points = $row['totalPoints'];

            if ($points !== $lastPoints) {
                $rank = $position;
                $lastPoints = $points;
            }

            /** @var User $user */
            $user = $this->em->getReference(User::class, $row['userId']);

            $leaderboard[] = [
                'user' => $user,
                'totalPoints' => $points,
                'rank' => $rank,
            ];
        }

        return $leaderboard;
    }
}
