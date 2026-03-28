<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\RuleSet;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RuleSetManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function save(RuleSet $ruleSet): void
    {
        $this->em->persist($ruleSet);
        $this->em->flush();
    }
}
