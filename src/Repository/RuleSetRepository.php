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

    /**
     * Returns a map of tournament IDs that have a rule set (single query).
     *
     * @param list<Tournament> $tournaments
     * @return array<int, true>
     */
    public function getHasRuleSetMap(array $tournaments): array
    {
        if (empty($tournaments)) {
            return [];
        }

        /** @var list<array{tournamentId: int|string}> $rows */
        $rows = $this->createQueryBuilder('rs')
            ->select('IDENTITY(rs.tournament) as tournamentId')
            ->where('rs.tournament IN (:tournaments)')
            ->setParameter('tournaments', $tournaments)
            ->getQuery()
            ->getResult();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['tournamentId']] = true;
        }

        return $map;
    }
}
