<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\PointEntry;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<PointEntry> */
final class PointEntryFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return PointEntry::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'user' => UserFactory::new(),
            'tournament' => TournamentFactory::new(),
            'game' => null,
            'specialBetRule' => null,
            'points' => 0.0,
            'reason' => self::faker()->sentence(),
        ];
    }
}
