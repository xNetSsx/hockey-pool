<?php

declare(strict_types=1);

namespace App\Enum;

enum BetValueType: string
{
    case Team = 'team';
    case String = 'string';
    case Integer = 'integer';

    public function label(): string
    {
        return match ($this) {
            self::Team => 'Tým',
            self::String => 'Text',
            self::Integer => 'Číslo',
        };
    }
}
