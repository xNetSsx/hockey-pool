<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Team;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<Team> */
final class TeamFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Team::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'name' => self::faker()->country(),
            'code' => strtoupper(self::faker()->unique()->lexify('???')),
            'flagEmoji' => null,
        ];
    }
}
