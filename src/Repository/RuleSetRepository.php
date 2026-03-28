<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RuleSet;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<RuleSet> */
class RuleSetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RuleSet::class);
    }

    public function findByTournament(Tournament $tournament): ?RuleSet
    {
        return $this->findOneBy(['tournament' => $tournament]);
    }
}
