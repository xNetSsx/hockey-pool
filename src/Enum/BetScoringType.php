<?php

declare(strict_types=1);

namespace App\Enum;

enum BetScoringType: string
{
    case ExactMatch = 'exact_match';
    case Closest = 'closest';

    public function label(): string
    {
        return match ($this) {
            self::ExactMatch => 'Přesná shoda (všichni správní)',
            self::Closest => 'Nejbližší tip (jeden vítěz)',
        };
    }
}
