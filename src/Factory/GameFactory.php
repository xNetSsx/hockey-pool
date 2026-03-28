<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Game;
use App\Enum\TournamentPhase;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/** @extends PersistentProxyObjectFactory<Game> */
final class GameFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Game::class;
    }

    /** @return array<string, mixed> */
    protected function defaults(): array
    {
        return [
            'tournament' => TournamentFactory::new(),
            'homeTeam' => TeamFactory::new(),
            'awayTeam' => TeamFactory::new(),
            'phase' => TournamentPhase::GroupStage,
            'playedAt' => self::faker()->dateTimeBetween('-1 year', 'now'),
            'homeScore' => null,
            'awayScore' => null,
            'isFinished' => false,
        ];
    }
}
