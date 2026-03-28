<?php

declare(strict_types=1);

namespace App\Story;

use App\Enum\TournamentStatus;
use App\Factory\TournamentFactory;
use Zenstruck\Foundry\Story;

final class TournamentStory extends Story
{
    public function build(): void
    {
        $this->addState('oh2026', TournamentFactory::createOne([
            'name' => 'Olympijské hry 2026',
            'year' => 2026,
            'slug' => 'oh-2026',
            'status' => TournamentStatus::Finished,
        ]));
    }
}
