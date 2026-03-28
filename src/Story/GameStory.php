<?php

declare(strict_types=1);

namespace App\Story;

use App\Enum\TournamentPhase;
use App\Factory\GameFactory;
use DateTime;
use DateTimeZone;
use Zenstruck\Foundry\Story;

final class GameStory extends Story
{
    public const string POOL = 'games';

    public function build(): void
    {
        $tournament = TournamentStory::get('oh2026');

        foreach (self::getMatchData() as $i => $data) {
            $home = TeamStory::get($data['home']);
            $away = TeamStory::get($data['away']);

            $this->addState(self::key($i, $data['home'], $data['away']), GameFactory::createOne([
                'tournament' => $tournament,
                'homeTeam' => $home,
                'awayTeam' => $away,
                'phase' => $data['phase'],
                'playedAt' => new DateTime($data['date'], new DateTimeZone('Europe/Prague')),
                'homeScore' => $data['hs'],
                'awayScore' => $data['as'],
                'isFinished' => true,
            ]), self::POOL);
        }
    }

    public static function key(int $index, string $home, string $away): string
    {
        return $index . '_' . $home . '_' . $away;
    }

    /** @return list<array{date: string, home: string, away: string, hs: int, as: int, phase: TournamentPhase}> */
    public static function getMatchData(): array
    {
        $g = TournamentPhase::GroupStage;
        $q = TournamentPhase::Quarterfinal;
        $s = TournamentPhase::Semifinal;
        $b = TournamentPhase::BronzeMedal;
        $f = TournamentPhase::GoldMedal;

        return [
            ['date' => '2026-02-11 16:40', 'home' => 'SVK', 'away' => 'FIN', 'hs' => 4, 'as' => 1, 'phase' => $g],
            ['date' => '2026-02-11 21:10', 'home' => 'SWE', 'away' => 'ITA', 'hs' => 5, 'as' => 2, 'phase' => $g],
            ['date' => '2026-02-12 12:10', 'home' => 'SUI', 'away' => 'FRA', 'hs' => 4, 'as' => 0, 'phase' => $g],
            ['date' => '2026-02-12 16:40', 'home' => 'CZE', 'away' => 'CAN', 'hs' => 0, 'as' => 5, 'phase' => $g],
            ['date' => '2026-02-12 21:10', 'home' => 'LAT', 'away' => 'USA', 'hs' => 1, 'as' => 5, 'phase' => $g],
            ['date' => '2026-02-12 21:10', 'home' => 'GER', 'away' => 'DEN', 'hs' => 3, 'as' => 1, 'phase' => $g],
            ['date' => '2026-02-13 12:10', 'home' => 'FIN', 'away' => 'SWE', 'hs' => 4, 'as' => 1, 'phase' => $g],
            ['date' => '2026-02-13 12:10', 'home' => 'ITA', 'away' => 'SVK', 'hs' => 2, 'as' => 3, 'phase' => $g],
            ['date' => '2026-02-13 16:40', 'home' => 'FRA', 'away' => 'CZE', 'hs' => 3, 'as' => 6, 'phase' => $g],
            ['date' => '2026-02-13 21:10', 'home' => 'CAN', 'away' => 'SUI', 'hs' => 5, 'as' => 1, 'phase' => $g],
            ['date' => '2026-02-14 12:10', 'home' => 'SWE', 'away' => 'SVK', 'hs' => 5, 'as' => 3, 'phase' => $g],
            ['date' => '2026-02-14 12:10', 'home' => 'GER', 'away' => 'LAT', 'hs' => 3, 'as' => 4, 'phase' => $g],
            ['date' => '2026-02-14 16:40', 'home' => 'FIN', 'away' => 'ITA', 'hs' => 11, 'as' => 0, 'phase' => $g],
            ['date' => '2026-02-14 21:10', 'home' => 'USA', 'away' => 'DEN', 'hs' => 6, 'as' => 3, 'phase' => $g],
            ['date' => '2026-02-15 12:10', 'home' => 'SUI', 'away' => 'CZE', 'hs' => 4, 'as' => 3, 'phase' => $g],
            ['date' => '2026-02-15 16:40', 'home' => 'CAN', 'away' => 'FRA', 'hs' => 10, 'as' => 2, 'phase' => $g],
            ['date' => '2026-02-15 19:10', 'home' => 'DEN', 'away' => 'LAT', 'hs' => 4, 'as' => 2, 'phase' => $g],
            ['date' => '2026-02-15 21:10', 'home' => 'USA', 'away' => 'GER', 'hs' => 5, 'as' => 1, 'phase' => $g],
            // Round of 16 + Quarterfinals
            ['date' => '2026-02-17 12:10', 'home' => 'SUI', 'away' => 'ITA', 'hs' => 3, 'as' => 0, 'phase' => $q],
            ['date' => '2026-02-17 12:10', 'home' => 'GER', 'away' => 'FRA', 'hs' => 5, 'as' => 1, 'phase' => $q],
            ['date' => '2026-02-17 16:40', 'home' => 'CZE', 'away' => 'DEN', 'hs' => 3, 'as' => 2, 'phase' => $q],
            ['date' => '2026-02-17 21:10', 'home' => 'SWE', 'away' => 'LAT', 'hs' => 5, 'as' => 1, 'phase' => $q],
            ['date' => '2026-02-18 12:10', 'home' => 'SVK', 'away' => 'GER', 'hs' => 6, 'as' => 2, 'phase' => $q],
            ['date' => '2026-02-18 14:10', 'home' => 'CAN', 'away' => 'CZE', 'hs' => 4, 'as' => 3, 'phase' => $q],
            ['date' => '2026-02-18 16:40', 'home' => 'FIN', 'away' => 'SUI', 'hs' => 3, 'as' => 2, 'phase' => $q],
            ['date' => '2026-02-18 21:10', 'home' => 'USA', 'away' => 'SWE', 'hs' => 2, 'as' => 1, 'phase' => $q],
            // Semifinals
            ['date' => '2026-02-20 16:40', 'home' => 'CAN', 'away' => 'FIN', 'hs' => 3, 'as' => 2, 'phase' => $s],
            ['date' => '2026-02-20 21:10', 'home' => 'USA', 'away' => 'SVK', 'hs' => 6, 'as' => 2, 'phase' => $s],
            // Bronze medal
            ['date' => '2026-02-21 20:40', 'home' => 'SVK', 'away' => 'FIN', 'hs' => 1, 'as' => 6, 'phase' => $b],
            // Gold medal
            ['date' => '2026-02-22 14:10', 'home' => 'CAN', 'away' => 'USA', 'hs' => 1, 'as' => 2, 'phase' => $f],
        ];
    }
}
