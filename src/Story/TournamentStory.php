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
        $this->addState('oh2022', TournamentFactory::createOne([
            'name' => 'Olympijské hry 2022',
            'year' => 2022,
            'slug' => 'oh-2022',
            'status' => TournamentStatus::Finished,
        ]));

        $this->addState('ms2022', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2022',
            'year' => 2022,
            'slug' => 'ms-2022',
            'status' => TournamentStatus::Finished,
        ]));

        $this->addState('ms2023', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2023',
            'year' => 2023,
            'slug' => 'ms-2023',
            'status' => TournamentStatus::Finished,
        ]));

        $this->addState('ms2024', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2024',
            'year' => 2024,
            'slug' => 'ms-2024',
            'status' => TournamentStatus::Finished,
        ]));

        $this->addState('ms2025', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2025',
            'year' => 2025,
            'slug' => 'ms-2025',
            'status' => TournamentStatus::Finished,
        ]));

        $this->addState('oh2026', TournamentFactory::createOne([
            'name' => 'Olympijské hry 2026',
            'year' => 2026,
            'slug' => 'oh-2026',
            'status' => TournamentStatus::Finished,
        ]));
    }
}
