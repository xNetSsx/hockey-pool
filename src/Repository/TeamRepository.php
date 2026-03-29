<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Team;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Team> */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @return list<Team>
     */
    public function findByTournament(Tournament $tournament): array
    {
        /** @var list<Team> $result */
        $result = $this->createQueryBuilder('t')
            ->join('App\Entity\Game', 'g', 'WITH', 'g.homeTeam = t OR g.awayTeam = t')
            ->where('g.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->orderBy('t.name', 'ASC')
            ->distinct()
            ->getQuery()
            ->getResult();

        return $result;
    }
}
