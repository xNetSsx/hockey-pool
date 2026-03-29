<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\RuleSet;
use App\Entity\SpecialBetRule;

/** @extends ServiceEntityRepository<Tournament> */
class TournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    public function findLatestWithRules(Tournament $exclude): ?Tournament
    {
        /** @var Tournament|null */
        return $this->createQueryBuilder('t')
            ->leftJoin(RuleSet::class, 'rs', 'WITH', 'rs.tournament = t')
            ->leftJoin(SpecialBetRule::class, 'sbr', 'WITH', 'sbr.tournament = t')
            ->where('t != :exclude')
            ->andWhere('rs.id IS NOT NULL OR sbr.id IS NOT NULL')
            ->setParameter('exclude', $exclude)
            ->orderBy('t.year', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
