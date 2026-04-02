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
