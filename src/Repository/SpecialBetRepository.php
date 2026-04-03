<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SpecialBet;
use App\Entity\Tournament;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<SpecialBet> */
class SpecialBetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialBet::class);
    }

    /**
     * @return list<SpecialBet>
     */
    public function findByTournament(Tournament $tournament): array
    {
        /** @var list<SpecialBet> $result */
        $result = $this->createQueryBuilder('sb')
            ->join('sb.rule', 'r')
            ->addSelect('r')
            ->where('r.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return array<int, SpecialBet> Indexed by rule ID
     */
    public function findByUserIndexedByRule(User $user, Tournament $tournament): array
    {
        /** @var list<SpecialBet> $bets */
        $bets = $this->createQueryBuilder('sb')
            ->join('sb.rule', 'r')
            ->addSelect('r')
            ->where('sb.user = :user')
            ->andWhere('r.tournament = :tournament')
            ->setParameter('user', $user)
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        $indexed = [];
        foreach ($bets as $bet) {
            $indexed[(int) $bet->getRule()->getId()] = $bet;
        }

        return $indexed;
    }

    /**
     * Batch-fetch all bets for a tournament, indexed by rule ID.
     *
     * @return array<int, list<SpecialBet>> ruleId => bets
     */
    public function findByTournamentIndexedByRule(Tournament $tournament): array
    {
        /** @var list<SpecialBet> $bets */
        $bets = $this->createQueryBuilder('sb')
            ->join('sb.rule', 'r')
            ->addSelect('r')
            ->where('r.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        /** @var array<int, list<SpecialBet>> $indexed */
        $indexed = [];
        foreach ($bets as $bet) {
            $ruleId = $bet->getRule()->getId();
            if (null === $ruleId) {
                continue;
            }

            $indexed[$ruleId][] = $bet;
        }

        return $indexed;
    }
}
