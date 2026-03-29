<?php

declare(strict_types=1);

namespace App\Enum;

enum BetScoringType: string
{
    case ExactMatch = 'exact_match';
    case Closest = 'closest';
    case Podium = 'podium';
    case AnyMatch = 'any_match';

    public function label(): string
    {
        return match ($this) {
            self::ExactMatch => 'Přesná shoda (všichni správní)',
            self::Closest => 'Nejbližší tip (jeden vítěz)',
            self::Podium => 'Medaile (1b top3 + 2b přesná pozice)',
            self::AnyMatch => 'Kdekoli v seznamu (body za správný výběr)',
        };
    }
}
