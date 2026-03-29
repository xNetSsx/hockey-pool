<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tournament;
use App\Entity\TournamentParticipant;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<TournamentParticipant> */
class TournamentParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentParticipant::class);
    }

    public function isParticipant(User $user, Tournament $tournament): bool
    {
        return null !== $this->findOneBy(['user' => $user, 'tournament' => $tournament]);
    }

    /**
     * @return list<TournamentParticipant>
     */
    public function findByTournament(Tournament $tournament): array
    {
        return $this->findBy(['tournament' => $tournament], ['joinedAt' => 'ASC']);
    }

    /**
     * @return list<int> User IDs
     */
    public function getParticipantUserIds(Tournament $tournament): array
    {
        /** @var list<array{userId: int|string}> $rows */
        $rows = $this->createQueryBuilder('tp')
            ->select('IDENTITY(tp.user) as userId')
            ->where('tp.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->getQuery()
            ->getResult();

        return array_map(static fn (array $row) => (int) $row['userId'], $rows);
    }
}
