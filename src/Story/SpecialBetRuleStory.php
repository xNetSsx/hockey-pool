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
        $tournament = TournamentStory::get('oh2026');

        $rules = [
            ['name' => 'Zlatá medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 3.0, 'sort' => 1, 'actualTeam' => 'USA'],
            ['name' => 'Stříbrná medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 3.0, 'sort' => 2, 'actualTeam' => 'CAN'],
            ['name' => 'Bronzová medaile', 'valueType' => BetValueType::Team, 'scoring' => BetScoringType::ExactMatch, 'points' => 3.0, 'sort' => 3, 'actualTeam' => 'FIN'],
            ['name' => 'Nejlepší Čech #1', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::ExactMatch, 'points' => 2.0, 'sort' => 4, 'actualString' => 'Pastrňák'],
            ['name' => 'Nejlepší Čech #2', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::ExactMatch, 'points' => 2.0, 'sort' => 5, 'actualString' => 'Nečas'],
            ['name' => 'Nejlepší Čech #3', 'valueType' => BetValueType::String, 'scoring' => BetScoringType::ExactMatch, 'points' => 2.0, 'sort' => 6, 'actualString' => 'Červenka'],
            ['name' => 'Celkem gólů ČR', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 7, 'actualInt' => 15],
            ['name' => 'Remízy v základní době', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 8, 'actualInt' => 5],
            ['name' => 'Trestné minuty Gudase', 'valueType' => BetValueType::Integer, 'scoring' => BetScoringType::Closest, 'points' => 2.0, 'sort' => 9, 'actualInt' => 4],
        ];

        foreach ($rules as $data) {
            $attrs = [
                'tournament' => $tournament,
                'name' => $data['name'],
                'valueType' => $data['valueType'],
                'scoringType' => $data['scoring'],
                'points' => $data['points'],
                'sortOrder' => $data['sort'],
            ];

            if (isset($data['actualTeam'])) {
                $attrs['actualTeamValue'] = TeamStory::get($data['actualTeam']);
            }

            if (isset($data['actualString'])) {
                $attrs['actualStringValue'] = $data['actualString'];
            }

            if (isset($data['actualInt'])) {
                $attrs['actualIntValue'] = $data['actualInt'];
            }

            $this->addState($data['name'], SpecialBetRuleFactory::createOne($attrs), self::POOL);
        }
    }
}
