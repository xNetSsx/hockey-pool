<?php

declare(strict_types=1);

namespace App\Service\Resolver\SpecialBet;

use App\Entity\PointEntry;
use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Enum\PointCategory;

trait PointEntryCreatorTrait
{
    private function createEntry(SpecialBet $bet, SpecialBetRule $rule, float $points, string $reason): PointEntry
    {
        return (new PointEntry())
            ->setUser($bet->getUser())
            ->setTournament($rule->getTournament())
            ->setSpecialBetRule($rule)
            ->setPoints($points)
            ->setReason($reason)
            ->setCategory(PointCategory::SpecialBet);
    }
}
