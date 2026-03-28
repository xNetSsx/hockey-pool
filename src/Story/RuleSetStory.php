<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\RuleSetFactory;
use Zenstruck\Foundry\Story;

final class RuleSetStory extends Story
{
    public function build(): void
    {
        $this->addState('oh2026', RuleSetFactory::createOne([
            'tournament' => TournamentStory::get('oh2026'),
            'winnerBasePoints' => 1.0,
            'wrongOpponentBonus' => 0.25,
            'exactScoreBonus' => 2.0,
            'prizes' => ['1' => 300, '2' => 150, '3' => 50],
        ]));
    }
}
