<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Game;
use App\Entity\PointEntry;
use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\PointCategory;
use App\Service\Resolver\MatchPointResolver;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<PointEntry> */
class PointEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PointEntry::class);
    }

    /**
     * @return list<PointEntry>
     */
    public function findByGame(Game $game): array
    {
        /** @var list<PointEntry> $result */
        $result = $this->createQueryBuilder('pe')
            ->leftJoin('pe.user', 'u')
            ->addSelect('u')
            ->where('pe.game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return array<int, list<PointEntry>> gameId => entries
     */
    public function findGameEntriesByTournamentIndexedByGameId(Tournament $tournament): array
    {
        /** @var list<PointEntry> $entries */
        $entries = $this->createQueryBuilder('pe')
            ->join('pe.game', 'g')
            ->addSelect('g')
            ->leftJoin('pe.user', 'u')
            ->addSelect('u')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.game IS NOT NULL')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        /** @var array<int, list<PointEntry>> $indexed */
        $indexed = [];
        foreach ($entries as $entry) {
            $game = $entry->getGame();
            if (null === $game) {
                continue;
            }

            $gameId = $game->getId();
            if (null === $gameId) {
                continue;
            }

            $indexed[$gameId][] = $entry;
        }

        return $indexed;
    }

    /**
     * @return list<PointEntry>
     */
    public function findSpecialBetEntries(Tournament $tournament): array
    {
        /** @var list<PointEntry> $result */
        $result = $this->createQueryBuilder('pe')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.game IS NULL')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return list<array{userId: int, totalPoints: float}>
     */
    public function getPointsGroupedByUser(Tournament $tournament): array
    {
        /** @var list<array{userId: int|string, totalPoints: float|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select('IDENTITY(pe.user) as userId, SUM(pe.points) as totalPoints')
            ->where('pe.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->groupBy('pe.user')
            ->orderBy('totalPoints', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(static fn (array $row) => [
            'userId' => (int) $row['userId'],
            'totalPoints' => (float) $row['totalPoints'],
        ], $rows);
    }

    /**
     * Points per match for a user, ordered by match date.
     *
     * @return list<array{gameId: int, points: float}>
     */
    public function getPointsPerMatch(User $user, Tournament $tournament): array
    {
        /** @var list<array{gameId: int|string, total: float|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select('IDENTITY(pe.game) as gameId, SUM(pe.points) as total')
            ->where('pe.user = :user')
            ->andWhere('pe.tournament = :tournament')
            ->andWhere('pe.game IS NOT NULL')
            ->setParameter('user', $user)
            ->setParameter('tournament', $tournament)
            ->groupBy('pe.game')
            ->getQuery()
            ->getResult();

        return array_map(static fn (array $row) => [
            'gameId' => (int) $row['gameId'],
            'points' => (float) $row['total'],
        ], $rows);
    }

    /**
     * Points per match for multiple users, indexed by user ID then game ID.
     *
     * @param list<User> $users
     * @return array<int, array<int, float>>
     */
    public function getPointsPerMatchByUsers(array $users, Tournament $tournament): array
    {
        if (empty($users)) {
            return [];
        }

        /** @var list<array{userId: int|string, gameId: int|string, total: float|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select('IDENTITY(pe.user) as userId, IDENTITY(pe.game) as gameId, SUM(pe.points) as total')
            ->where('pe.user IN (:users)')
            ->andWhere('pe.tournament = :tournament')
            ->andWhere('pe.game IS NOT NULL')
            ->setParameter('users', $users)
            ->setParameter('tournament', $tournament)
            ->groupBy('pe.user, pe.game')
            ->getQuery()
            ->getResult();

        /** @var array<int, array<int, float>> $indexed */
        $indexed = [];
        foreach ($rows as $row) {
            $indexed[(int) $row['userId']][(int) $row['gameId']] = (float) $row['total'];
        }

        return $indexed;
    }

    /**
     * Returns points per user per game for a tournament, for building timelines.
     *
     * @return list<array{userId: int, username: string, gameId: int, playedAt: string, homeCode: string, awayCode: string, points: float}>
     */
    public function getTimelineData(Tournament $tournament): array
    {
        /** @var list<array{userId: int|string, username: string, gameId: int|string, playedAt: DateTimeInterface, homeCode: string, awayCode: string, total: float|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select(
                'IDENTITY(pe.user) as userId',
                'u.username',
                'IDENTITY(pe.game) as gameId',
                'g.playedAt',
                'ht.code as homeCode',
                'at.code as awayCode',
                'SUM(pe.points) as total',
            )
            ->join('pe.user', 'u')
            ->join('pe.game', 'g')
            ->join('g.homeTeam', 'ht')
            ->join('g.awayTeam', 'at')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.game IS NOT NULL')
            ->setParameter('tournament', $tournament)
            ->groupBy('pe.user, pe.game, u.username, g.playedAt, ht.code, at.code')
            ->orderBy('g.playedAt', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(static fn (array $row) => [
            'userId' => (int) $row['userId'],
            'username' => $row['username'],
            'gameId' => (int) $row['gameId'],
            'playedAt' => $row['playedAt']->format('Y-m-d H:i'),
            'homeCode' => $row['homeCode'],
            'awayCode' => $row['awayCode'],
            'points' => (float) $row['total'],
        ], $rows);
    }

    /**
     * Combined tiebreaker counts (exact scores + correct winners) per user in a single query.
     *
     * @return array<int, array{exactScores: int, correctWinners: int}>
     */
    public function getTiebreakCountsByUser(Tournament $tournament): array
    {
        /** @var list<array{userId: int|string, reason: string, cnt: int|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select('IDENTITY(pe.user) as userId, pe.reason, COUNT(pe.id) as cnt')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.reason IN (:reasons) OR pe.category IN (:categories)')
            ->setParameter('tournament', $tournament)
            ->setParameter('reasons', [
                MatchPointResolver::REASON_EXACT_SCORE_BONUS,
                MatchPointResolver::REASON_CORRECT_WINNER,
            ])
            ->setParameter('categories', [
                PointCategory::CorrectWinner,
                PointCategory::ExactScoreBonus,
            ])
            ->groupBy('pe.user, pe.reason')
            ->getQuery()
            ->getResult();

        /** @var array<int, array{exactScores: int, correctWinners: int}> $result */
        $result = [];
        foreach ($rows as $row) {
            $userId = (int) $row['userId'];
            if (!isset($result[$userId])) {
                $result[$userId] = ['exactScores' => 0, 'correctWinners' => 0];
            }

            if ($row['reason'] === MatchPointResolver::REASON_EXACT_SCORE_BONUS) {
                $result[$userId]['exactScores'] = (int) $row['cnt'];
            } elseif ($row['reason'] === MatchPointResolver::REASON_CORRECT_WINNER) {
                $result[$userId]['correctWinners'] = (int) $row['cnt'];
            }
        }

        return $result;
    }

    /**
     * Points earned today per user (from matches played today).
     *
     * @return array<int, float> userId => points
     */
    public function getTodayPointsByUser(Tournament $tournament): array
    {
        $today = new DateTimeImmutable('today');
        $tomorrow = new DateTimeImmutable('tomorrow');

        /** @var list<array{userId: int|string, total: float|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select('IDENTITY(pe.user) as userId, SUM(pe.points) as total')
            ->join('pe.game', 'g')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.game IS NOT NULL')
            ->andWhere('g.playedAt >= :today')
            ->andWhere('g.playedAt < :tomorrow')
            ->setParameter('tournament', $tournament)
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->groupBy('pe.user')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['userId']] = (float) $row['total'];
        }

        return $result;
    }

    /**
     * Highest total points any user scored on a single match.
     *
     * @return array{username: string, points: float, gameId: int}|null
     */
    public function findHighestMatchScore(Tournament $tournament): ?array
    {
        /** @var array{username: string, gameId: int|string, total: float|string}|null $row */
        $row = $this->createQueryBuilder('pe')
            ->select('u.username, IDENTITY(pe.game) as gameId, SUM(pe.points) as total')
            ->join('pe.user', 'u')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.game IS NOT NULL')
            ->setParameter('tournament', $tournament)
            ->groupBy('pe.user, u.username, pe.game')
            ->orderBy('total', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $row) {
            return null;
        }

        return [
            'username' => $row['username'],
            'points' => (float) $row['total'],
            'gameId' => (int) $row['gameId'],
        ];
    }

    /**
     * User with the most exact score predictions in a tournament.
     *
     * @return array{username: string, count: int}|null
     */
    public function findMostExactPredictions(Tournament $tournament): ?array
    {
        /** @var array{username: string, cnt: int|string}|null $row */
        $row = $this->createQueryBuilder('pe')
            ->select('u.username, COUNT(pe.id) as cnt')
            ->join('pe.user', 'u')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.reason = :reason')
            ->setParameter('tournament', $tournament)
            ->setParameter('reason', MatchPointResolver::REASON_EXACT_SCORE_BONUS)
            ->groupBy('pe.user, u.username')
            ->orderBy('cnt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $row) {
            return null;
        }

        return [
            'username' => $row['username'],
            'count' => (int) $row['cnt'],
        ];
    }
}
