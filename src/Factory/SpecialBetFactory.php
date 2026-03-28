<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\SpecialBet;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<SpecialBet> */
final class SpecialBetFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return SpecialBet::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'user' => UserFactory::new(),
            'rule' => SpecialBetRuleFactory::new(),
            'teamValue' => null,
            'stringValue' => null,
            'intValue' => null,
        ];
    }
}
