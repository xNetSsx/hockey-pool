<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Game;
use App\Entity\PointEntry;
use App\Entity\Tournament;
use App\Entity\User;
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
        return $this->findBy(['game' => $game]);
    }

    /**
     * @return list<PointEntry>
     */
    public function findSpecialBetEntries(Tournament $tournament): array
    {
        /** @var list<PointEntry> */
        return $this->createQueryBuilder('pe')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.game IS NULL')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();
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
     * Count of 'Exact score bonus' entries per user for tiebreaking.
     *
     * @return array<int, int> userId => count
     */
    public function countExactScoresByUser(Tournament $tournament): array
    {
        /** @var list<array{userId: int|string, cnt: int|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select('IDENTITY(pe.user) as userId, COUNT(pe.id) as cnt')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.reason = :reason')
            ->setParameter('tournament', $tournament)
            ->setParameter('reason', 'Exact score bonus')
            ->groupBy('pe.user')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['userId']] = (int) $row['cnt'];
        }

        return $result;
    }

    /**
     * Count of 'Correct winner' entries per user for tiebreaking.
     *
     * @return array<int, int> userId => count
     */
    public function countCorrectWinnersByUser(Tournament $tournament): array
    {
        /** @var list<array{userId: int|string, cnt: int|string}> $rows */
        $rows = $this->createQueryBuilder('pe')
            ->select('IDENTITY(pe.user) as userId, COUNT(pe.id) as cnt')
            ->where('pe.tournament = :tournament')
            ->andWhere('pe.reason = :reason')
            ->setParameter('tournament', $tournament)
            ->setParameter('reason', 'Correct winner')
            ->groupBy('pe.user')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['userId']] = (int) $row['cnt'];
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
        $today = new \DateTimeImmutable('today');
        $tomorrow = new \DateTimeImmutable('tomorrow');

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
            ->setParameter('reason', 'Exact score bonus')
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
