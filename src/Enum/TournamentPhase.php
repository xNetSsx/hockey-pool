<?php

declare(strict_types=1);

namespace App\Enum;

enum TournamentPhase: string
{
    case GroupStage = 'group_stage';
    case Quarterfinal = 'quarterfinal';
    case Semifinal = 'semifinal';
    case BronzeMedal = 'bronze_medal';
    case GoldMedal = 'gold_medal';

    public function label(): string
    {
        return match ($this) {
            self::GroupStage => 'Základní skupina',
            self::Quarterfinal => 'Čtvrtfinále',
            self::Semifinal => 'Semifinále',
            self::BronzeMedal => 'O bronz',
            self::GoldMedal => 'Finále',
        };
    }
}
