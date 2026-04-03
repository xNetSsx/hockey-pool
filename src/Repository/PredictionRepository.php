<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\Tournament;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Prediction> */
class PredictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prediction::class);
    }

    /**
     * @return list<Prediction>
     */
    public function findByGame(Game $game): array
    {
        return $this->findBy(['game' => $game]);
    }

    /**
     * Batch-fetch predictions for a set of games, indexed by game ID.
     *
     * @param list<Game> $games
     * @return array<int, list<Prediction>>
     */
    public function findByGamesIndexedByGameId(array $games): array
    {
        if (empty($games)) {
            return [];
        }

        /** @var list<Prediction> $predictions */
        $predictions = $this->createQueryBuilder('p')
            ->leftJoin('p.game', 'g')
            ->addSelect('g')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->where('p.game IN (:games)')
            ->setParameter('games', $games)
            ->getQuery()
            ->getResult();

        /** @var array<int, list<Prediction>> $indexed */
        $indexed = [];
        foreach ($predictions as $prediction) {
            $gameId = $prediction->getGame()->getId();
            if (null === $gameId) {
                continue;
            }

            $indexed[(int) $gameId][] = $prediction;
        }

        return $indexed;
    }

    /**
     * Batch-fetch predictions for multiple users in a tournament, indexed by user ID then game ID.
     *
     * @param list<User> $users
     * @return array<int, array<int, Prediction>>
     */
    public function findByUsersAndTournamentIndexedByUserId(array $users, Tournament $tournament): array
    {
        if (empty($users)) {
            return [];
        }

        /** @var list<Prediction> $predictions */
        $predictions = $this->createQueryBuilder('p')
            ->join('p.game', 'g')
            ->where('p.user IN (:users)')
            ->andWhere('g.tournament = :tournament')
            ->setParameter('users', $users)
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        /** @var array<int, array<int, Prediction>> $indexed */
        $indexed = [];
        foreach ($predictions as $prediction) {
            $userId = (int) $prediction->getUser()->getId();
            $gameId = (int) $prediction->getGame()->getId();
            $indexed[$userId][$gameId] = $prediction;
        }

        return $indexed;
    }

    /**
     * Count of finished-game predictions per user across multiple tournaments.
     *
     * @param list<Tournament> $tournaments
     * @return array<int, int> userId => count
     */
    public function getFinishedPredictionCountsByUsers(array $tournaments): array
    {
        if ([] === $tournaments) {
            return [];
        }

        /** @var list<array{userId: int|string, cnt: int|string}> $rows */
        $rows = $this->createQueryBuilder('p')
            ->select('IDENTITY(p.user) as userId, COUNT(p.id) as cnt')
            ->join('p.game', 'g')
            ->where('g.tournament IN (:tournaments)')
            ->andWhere('g.isFinished = true')
            ->setParameter('tournaments', $tournaments)
            ->groupBy('p.user')
            ->getQuery()
            ->getResult();

        /** @var array<int, int> $result */
        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['userId']] = (int) $row['cnt'];
        }

        return $result;
    }

    /**
     * Returns predictions for a user in a tournament, indexed by game ID.
     *
     * @return array<int, Prediction>
     */
    public function findByUserIndexedByGame(User $user, Tournament $tournament): array
    {
        /** @var list<Prediction> $predictions */
        $predictions = $this->createQueryBuilder('p')
            ->join('p.game', 'g')
            ->where('p.user = :user')
            ->andWhere('g.tournament = :tournament')
            ->setParameter('user', $user)
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        $indexed = [];
        foreach ($predictions as $prediction) {
            $indexed[(int) $prediction->getGame()->getId()] = $prediction;
        }

        return $indexed;
    }
}
