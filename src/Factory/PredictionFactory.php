<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Prediction;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<Prediction> */
final class PredictionFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Prediction::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'user' => UserFactory::new(),
            'game' => GameFactory::new(),
            'homeScore' => self::faker()->numberBetween(0, 10),
            'awayScore' => self::faker()->numberBetween(0, 10),
        ];
    }
}
