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

    /**
     * Returns a map of tournament IDs that have at least one special bet rule (single query).
     *
     * @param list<Tournament> $tournaments
     * @return array<int, true>
     */
    public function getHasSpecialBetRulesMap(array $tournaments): array
    {
        if (empty($tournaments)) {
            return [];
        }

        /** @var list<array{tournamentId: int|string}> $rows */
        $rows = $this->createQueryBuilder('r')
            ->select('IDENTITY(r.tournament) as tournamentId')
            ->where('r.tournament IN (:tournaments)')
            ->setParameter('tournaments', $tournaments)
            ->groupBy('r.tournament')
            ->getQuery()
            ->getResult();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['tournamentId']] = true;
        }

        return $map;
    }
}
