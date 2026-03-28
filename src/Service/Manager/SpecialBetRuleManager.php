<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\SpecialBetRule;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SpecialBetRuleManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function save(SpecialBetRule $rule): void
    {
        $this->em->persist($rule);
        $this->em->flush();
    }
}
