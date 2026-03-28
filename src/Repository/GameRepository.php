<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Game> */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @return list<Game>
     */
    public function findUpcoming(Tournament $tournament, int $limit = 5): array
    {
        /** @var list<Game> $result */
        $result = $this->createQueryBuilder('g')
            ->where('g.tournament = :tournament')
            ->andWhere('g.isFinished = false')
            ->andWhere('g.playedAt > :now')
            ->setParameter('tournament', $tournament)
            ->setParameter('now', new DateTime())
            ->orderBy('g.playedAt', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * Returns all matches for a tournament grouped by phase, ordered by date.
     *
     * @return array<string, list<Game>>
     */
    public function findByTournamentGroupedByPhase(Tournament $tournament): array
    {
        /** @var list<Game> $games */
        $games = $this->createQueryBuilder('g')
            ->where('g.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->orderBy('g.playedAt', 'ASC')
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($games as $game) {
            $grouped[$game->getPhase()->value][] = $game;
        }

        // Ensure phase order matches enum order
        $ordered = [];
        foreach (TournamentPhase::cases() as $phase) {
            if (isset($grouped[$phase->value])) {
                $ordered[$phase->value] = $grouped[$phase->value];
            }
        }

        return $ordered;
    }

    public function countFinished(Tournament $tournament): int
    {
        return (int) $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.tournament = :tournament')
            ->andWhere('g.isFinished = true')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countTotal(Tournament $tournament): int
    {
        return (int) $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findFirstMatchDate(Tournament $tournament): ?DateTime
    {
        /** @var Game|null $game */
        $game = $this->createQueryBuilder('g')
            ->where('g.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->orderBy('g.playedAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $game?->getPlayedAt();
    }
}
