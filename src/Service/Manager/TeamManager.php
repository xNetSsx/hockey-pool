<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

final readonly class TeamManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function save(Team $team): void
    {
        $this->em->persist($team);
        $this->em->flush();
    }
}
