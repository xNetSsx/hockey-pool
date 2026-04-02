<?php

declare(strict_types=1);

namespace App\Enum;

enum PointCategory: string
{
    case CorrectWinner = 'correct_winner';
    case OpponentBonus = 'opponent_bonus';
    case ExactScoreBonus = 'exact_score_bonus';
    case SpecialBet = 'special_bet';
}
