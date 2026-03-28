<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\RuleSet;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<RuleSet> */
final class RuleSetFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return RuleSet::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'tournament' => TournamentFactory::new(),
            'winnerBasePoints' => 1.0,
            'wrongOpponentBonus' => 0.25,
            'exactScoreBonus' => 2.0,
            'prizes' => ['1' => 300, '2' => 150, '3' => 50],
        ];
    }
}
