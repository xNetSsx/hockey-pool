<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\Tournament;
use App\Entity\TournamentParticipant;
use App\Entity\User;
use App\Repository\TournamentParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class TournamentParticipantManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private TournamentParticipantRepository $participantRepository,
    ) {
    }

    /**
     * Adds a user as a participant. Returns false when the user is already a participant.
     */
    public function add(User $user, Tournament $tournament): bool
    {
        if ($this->participantRepository->isParticipant($user, $tournament)) {
            return false;
        }

        $participant = new TournamentParticipant();
        $participant->setUser($user);
        $participant->setTournament($tournament);
        $this->em->persist($participant);
        $this->em->flush();

        return true;
    }

    public function remove(TournamentParticipant $participant): void
    {
        $this->em->remove($participant);
        $this->em->flush();
    }

    public function togglePaid(TournamentParticipant $participant): void
    {
        $participant->setPaid(!$participant->isPaid());
        $this->em->flush();
    }
}
