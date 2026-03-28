<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\SpecialBet;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SpecialBetManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function save(SpecialBet $bet): void
    {
        $this->em->persist($bet);
        $this->em->flush();
    }

    /**
     * @param list<SpecialBet> $bets
     */
    public function saveAll(array $bets): void
    {
        foreach ($bets as $bet) {
            $this->em->persist($bet);
        }

        $this->em->flush();
    }
}
