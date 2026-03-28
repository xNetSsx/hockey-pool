<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Tournament;
use App\Enum\TournamentStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<Tournament> */
final class TournamentFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Tournament::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'name' => self::faker()->words(3, true),
            'year' => self::faker()->year(),
            'slug' => self::faker()->unique()->slug(),
            'status' => TournamentStatus::Upcoming,
        ];
    }
}
