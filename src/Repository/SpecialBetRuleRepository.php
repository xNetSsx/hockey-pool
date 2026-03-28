<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SpecialBetRule;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<SpecialBetRule> */
class SpecialBetRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialBetRule::class);
    }

    /**
     * @return list<SpecialBetRule>
     */
    public function findByTournament(Tournament $tournament): array
    {
        /** @var list<SpecialBetRule> $result */
        $result = $this->createQueryBuilder('r')
            ->where('r.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->orderBy('r.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
