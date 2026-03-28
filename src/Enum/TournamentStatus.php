<?php

declare(strict_types=1);

namespace App\Enum;

enum TournamentStatus: string
{
    case Upcoming = 'upcoming';
    case InProgress = 'in_progress';
    case Finished = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::Upcoming => 'Nadcházející',
            self::InProgress => 'Probíhá',
            self::Finished => 'Ukončen',
        };
    }
}
