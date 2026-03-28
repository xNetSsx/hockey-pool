<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\SpecialBetRule;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<SpecialBetRule> */
final class SpecialBetRuleFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return SpecialBetRule::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'tournament' => TournamentFactory::new(),
            'name' => self::faker()->words(3, true),
            'valueType' => BetValueType::Integer,
            'scoringType' => BetScoringType::ExactMatch,
            'points' => 2.0,
            'sortOrder' => 0,
        ];
    }
}
