<?php

declare(strict_types=1);

namespace App\Story;

use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Factory\SpecialBetRuleFactory;
use Zenstruck\Foundry\Story;

final class SpecialBetRuleStory extends Story
{
    public const string POOL = 'rules';

    public function build(): void
    {
        $this->buildOh2022();
        $this->buildMs2022();
        $this->buildMs2023();
        $this->buildMs2024();
        $this->buildMs2025();
        $this->buildOh2026();
    }

    private function buildOh2022(): void
    {
        $tournament = TournamentStory::get('oh2022');

        $rules = [
            ['name' => 'Zlatá medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 1, 'actualTeam' => 'FIN'],
            ['name' => 'Stříbrná medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 2, 'actualTeam' => 'ROC'],
            ['name' => 'Bronzová medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 3, 'actualTeam' => 'SVK'],
            ['name' => 'Nejlepší Čech #1', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 4, 'actualString' => 'Klok'],
            ['name' => 'Nejlepší Čech #2', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 5, 'actualString' => 'Krejčí'],
            ['name' => 'Nejlepší Čech #3', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 6, 'actualString' => 'Červenka'],
        ];

        $this->createRules($tournament, $rules, 'oh2022');
    }

    private function buildMs2022(): void
    {
        $tournament = TournamentStory::get('ms2022');

        $rules = [
            ['name' => 'Zlatá medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 1, 'actualTeam' => 'FIN'],
            ['name' => 'Stříbrná medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 2, 'actualTeam' => 'CAN'],
            ['name' => 'Bronzová medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 3, 'actualTeam' => 'CZE'],
            ['name' => 'Nejlepší Čech #1', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 4, 'actualString' => 'Krejčí'],
            ['name' => 'Nejlepší Čech #2', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 5, 'actualString' => 'Červenka'],
            ['name' => 'Nejlepší Čech #3', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 6, 'actualString' => 'Vejmelka'],
            ['name' => 'Počet gólů české reprezentace', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 7, 'actualInt' => 32],
            ['name' => 'Sestupující tým 1', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 2.0, 'sort' => 8, 'actualTeam' => 'ITA'],
            ['name' => 'Sestupující tým 2', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 2.0, 'sort' => 9, 'actualTeam' => 'GBR'],
        ];

        $this->createRules($tournament, $rules, 'ms2022');
    }

    private function buildMs2023(): void
    {
        $tournament = TournamentStory::get('ms2023');

        $rules = [
            ['name' => 'Zlatá medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 1, 'actualTeam' => 'CAN'],
            ['name' => 'Stříbrná medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 2, 'actualTeam' => 'GER'],
            ['name' => 'Bronzová medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 3, 'actualTeam' => 'LAT'],
            ['name' => 'Nejlepší Čech #1', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 4, 'actualString' => 'Červenka'],
            ['name' => 'Nejlepší Čech #2', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 5, 'actualString' => 'Kubalík'],
            ['name' => 'Nejlepší Čech #3', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 6, 'actualString' => 'Kempný'],
            ['name' => 'Počet gólů české reprezentace', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 7, 'actualInt' => 22],
            ['name' => 'Počet zápasů rozhodnutých na nájezdy', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 8, 'actualInt' => 6],
            ['name' => 'Sestupující tým 1', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 2.0, 'sort' => 9, 'actualTeam' => 'SLO'],
            ['name' => 'Sestupující tým 2', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 2.0, 'sort' => 10, 'actualTeam' => 'HUN'],
        ];

        $this->createRules($tournament, $rules, 'ms2023');
    }

    private function buildMs2024(): void
    {
        $tournament = TournamentStory::get('ms2024');

        $rules = [
            ['name' => 'Zlatá medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 1, 'actualTeam' => 'CZE'],
            ['name' => 'Stříbrná medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 2, 'actualTeam' => 'SUI'],
            ['name' => 'Bronzová medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 3, 'actualTeam' => 'SWE'],
            ['name' => 'Nejlepší Čech #1', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 4, 'actualString' => 'Červenka'],
            ['name' => 'Nejlepší Čech #2', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 5, 'actualString' => 'Kubalík'],
            ['name' => 'Nejlepší Čech #3', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 6, 'actualString' => 'Sedlák'],
            // Group A: 1.CAN, 2.SUI, 3.CZE, 4.FIN, 5.NOR, 6.AUS, 7.DEN, 8.GBR
            ['name' => 'Pořadí skupiny A - 1. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 7, 'actualTeam' => 'CAN'],
            ['name' => 'Pořadí skupiny A - 2. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 8, 'actualTeam' => 'SUI'],
            ['name' => 'Pořadí skupiny A - 3. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 9, 'actualTeam' => 'CZE'],
            ['name' => 'Pořadí skupiny A - 4. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 10, 'actualTeam' => 'FIN'],
            ['name' => 'Pořadí skupiny A - 5. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 11, 'actualTeam' => 'NOR'],
            ['name' => 'Pořadí skupiny A - 6. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 12, 'actualTeam' => 'AUS'],
            ['name' => 'Pořadí skupiny A - 7. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 13, 'actualTeam' => 'DEN'],
            ['name' => 'Pořadí skupiny A - 8. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 14, 'actualTeam' => 'GBR'],
            // Group B: 1.SWE, 2.USA, 3.GER, 4.SVK, 5.LAT, 6.KAZ, 7.FRA, 8.POL
            ['name' => 'Pořadí skupiny B - 1. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 15, 'actualTeam' => 'SWE'],
            ['name' => 'Pořadí skupiny B - 2. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 16, 'actualTeam' => 'USA'],
            ['name' => 'Pořadí skupiny B - 3. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 17, 'actualTeam' => 'GER'],
            ['name' => 'Pořadí skupiny B - 4. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 18, 'actualTeam' => 'SVK'],
            ['name' => 'Pořadí skupiny B - 5. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 19, 'actualTeam' => 'LAT'],
            ['name' => 'Pořadí skupiny B - 6. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 20, 'actualTeam' => 'KAZ'],
            ['name' => 'Pořadí skupiny B - 7. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 21, 'actualTeam' => 'FRA'],
            ['name' => 'Pořadí skupiny B - 8. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 22, 'actualTeam' => 'POL'],
            ['name' => 'Počet gólů české reprezentace', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 23, 'actualInt' => 37],
            ['name' => 'Remízy v základní době', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 24, 'actualInt' => 10],
            ['name' => 'Trestné minuty Gudase', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 25, 'actualInt' => 18],
        ];

        $this->createRules($tournament, $rules, 'ms2024');
    }

    private function buildMs2025(): void
    {
        $tournament = TournamentStory::get('ms2025');

        $rules = [
            ['name' => 'Zlatá medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 1, 'actualTeam' => 'USA'],
            ['name' => 'Stříbrná medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 2, 'actualTeam' => 'SUI'],
            ['name' => 'Bronzová medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 3, 'actualTeam' => 'SWE'],
            ['name' => 'Nejlepší Čech #1', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 4, 'actualString' => 'Pastrňák'],
            ['name' => 'Nejlepší Čech #2', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 5, 'actualString' => 'Červenka'],
            ['name' => 'Nejlepší Čech #3', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 6, 'actualString' => 'Sedlák'],
            // Group A: 1.CAN, 2.SWE, 3.FIN, 4.AUS, 5.LAT, 6.SVK, 7.SLO, 8.FRA
            ['name' => 'Pořadí skupiny A - 1. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 7, 'actualTeam' => 'CAN'],
            ['name' => 'Pořadí skupiny A - 2. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 8, 'actualTeam' => 'SWE'],
            ['name' => 'Pořadí skupiny A - 3. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 9, 'actualTeam' => 'FIN'],
            ['name' => 'Pořadí skupiny A - 4. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 10, 'actualTeam' => 'AUS'],
            ['name' => 'Pořadí skupiny A - 5. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 11, 'actualTeam' => 'LAT'],
            ['name' => 'Pořadí skupiny A - 6. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 12, 'actualTeam' => 'SVK'],
            ['name' => 'Pořadí skupiny A - 7. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 13, 'actualTeam' => 'SLO'],
            ['name' => 'Pořadí skupiny A - 8. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 14, 'actualTeam' => 'FRA'],
            // Group B: 1.SUI, 2.USA, 3.CZE, 4.DEN, 5.GER, 6.NOR, 7.HUN, 8.KAZ
            ['name' => 'Pořadí skupiny B - 1. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 15, 'actualTeam' => 'SUI'],
            ['name' => 'Pořadí skupiny B - 2. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 16, 'actualTeam' => 'USA'],
            ['name' => 'Pořadí skupiny B - 3. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 17, 'actualTeam' => 'CZE'],
            ['name' => 'Pořadí skupiny B - 4. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 18, 'actualTeam' => 'DEN'],
            ['name' => 'Pořadí skupiny B - 5. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 19, 'actualTeam' => 'GER'],
            ['name' => 'Pořadí skupiny B - 6. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 20, 'actualTeam' => 'NOR'],
            ['name' => 'Pořadí skupiny B - 7. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 21, 'actualTeam' => 'HUN'],
            ['name' => 'Pořadí skupiny B - 8. místo', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 1.0, 'sort' => 22, 'actualTeam' => 'KAZ'],
            ['name' => 'Počet gólů české reprezentace', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 23, 'actualInt' => 37],
            ['name' => 'Remízy v základní době', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 24, 'actualInt' => 8],
        ];

        $this->createRules($tournament, $rules, 'ms2025');
    }

    private function buildOh2026(): void
    {
        $tournament = TournamentStory::get('oh2026');

        $rules = [
            ['name' => 'Zlatá medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 1, 'actualTeam' => 'USA'],
            ['name' => 'Stříbrná medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 2, 'actualTeam' => 'CAN'],
            ['name' => 'Bronzová medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::Podium, 'points' => 3.0, 'sort' => 3, 'actualTeam' => 'FIN'],
            ['name' => 'Nejlepší Čech #1', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 4, 'actualString' => 'Pastrňák'],
            ['name' => 'Nejlepší Čech #2', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 5, 'actualString' => 'Nečas'],
            ['name' => 'Nejlepší Čech #3', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::AnyMatch, 'points' => 2.0, 'sort' => 6, 'actualString' => 'Červenka'],
            ['name' => 'Celkem gólů ČR', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 7, 'actualInt' => 15],
            ['name' => 'Remízy v základní době', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 8, 'actualInt' => 5],
            ['name' => 'Trestné minuty Gudase', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 9, 'actualInt' => 4],
        ];

        $this->createRules($tournament, $rules, 'oh2026');
    }

    /**
     * @param array<int, array<string, mixed>> $rules
     */
    private function createRules(mixed $tournament, array $rules, string $prefix): void
    {
        foreach ($rules as $data) {
            $attrs = [
                'tournament' => $tournament,
                'name' => $data['name'],
                'valueType' => $data['valueType'],
                'scoringType' => $data['scoring'],
                'points' => $data['points'],
                'sortOrder' => $data['sort'],
            ];

            if (isset($data['actualTeam']) && is_string($data['actualTeam'])) {
                $attrs['actualTeamValue'] = TeamStory::get($data['actualTeam']);
            }

            if (isset($data['actualString'])) {
                $attrs['actualStringValue'] = $data['actualString'];
            }

            if (isset($data['actualInt'])) {
                $attrs['actualIntValue'] = $data['actualInt'];
            }

            $stateName = $prefix . ':' . $data['name'];
            $this->addState($stateName, SpecialBetRuleFactory::createOne($attrs), self::POOL);
        }
    }
}
