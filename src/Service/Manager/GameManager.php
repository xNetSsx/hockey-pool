<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

final readonly class GameManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function save(Game $game): void
    {
        $this->em->persist($game);
        $this->em->flush();
    }

    /**
     * @param list<Game> $games
     */
    public function saveAll(array $games): void
    {
        foreach ($games as $game) {
            $this->em->persist($game);
        }

        $this->em->flush();
    }
}
