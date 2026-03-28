<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\SpecialBetFactory;
use Zenstruck\Foundry\Story;

final class SpecialBetStory extends Story
{
    public function build(): void
    {
        $this->loadTeamBets();
        $this->loadStringBets();
        $this->loadIntBets();
    }

    private function loadTeamBets(): void
    {
        // gold / silver / bronze per user
        $medals = [
            'Ondra'   => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'SWE', 'Bronzová medaile' => 'USA'],
            'Táda'    => ['Zlatá medaile' => 'CZE', 'Stříbrná medaile' => 'CAN', 'Bronzová medaile' => 'SWE'],
            'Martin'  => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'SWE', 'Bronzová medaile' => 'CZE'],
            'Pavel'   => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'CZE', 'Bronzová medaile' => 'USA'],
            'Váca'    => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'SWE', 'Bronzová medaile' => 'CZE'],
            'Kuba'    => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'USA', 'Bronzová medaile' => 'CZE'],
            'Mééča'   => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'SWE', 'Bronzová medaile' => 'USA'],
            'Honza S' => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'USA', 'Bronzová medaile' => 'CZE'],
            'Mates'   => ['Zlatá medaile' => 'CAN', 'Stříbrná medaile' => 'USA', 'Bronzová medaile' => 'CZE'],
            'Fanda'   => ['Zlatá medaile' => 'CZE', 'Stříbrná medaile' => 'CAN', 'Bronzová medaile' => 'USA'],
        ];

        foreach ($medals as $username => $picks) {
            $user = UserStory::get($username);

            foreach ($picks as $ruleName => $teamCode) {
                SpecialBetFactory::createOne([
                    'user' => $user,
                    'rule' => SpecialBetRuleStory::get($ruleName),
                    'teamValue' => TeamStory::get($teamCode),
                ]);
            }
        }
    }

    private function loadStringBets(): void
    {
        $bestCzech = [
            'Ondra'   => ['Pastrňák', 'Nečas', 'Hertl'],
            'Táda'    => ['Pastrňák', 'Nečas', 'Červenka'],
            'Martin'  => ['Pastrňák', 'Nečas', 'Červenka'],
            'Pavel'   => ['Pastrňák', 'Nečas', 'Červenka'],
            'Váca'    => ['Pastrňák', 'Nečas', 'Červenka'],
            'Kuba'    => ['Sedlák', 'Nečas', 'Červenka'],
            'Mééča'   => ['Pastrňák', 'Nečas', 'Palát'],
            'Honza S' => ['Pastrňák', 'Kubalík', 'Červenka'],
            'Mates'   => ['Pastrňák', 'Nečas', 'Červenka'],
            'Fanda'   => ['Pastrňák', 'Nečas', 'Červenka'],
        ];

        $ruleNames = ['Nejlepší Čech #1', 'Nejlepší Čech #2', 'Nejlepší Čech #3'];

        foreach ($bestCzech as $username => $players) {
            $user = UserStory::get($username);

            foreach ($ruleNames as $i => $ruleName) {
                SpecialBetFactory::createOne([
                    'user' => $user,
                    'rule' => SpecialBetRuleStory::get($ruleName),
                    'stringValue' => $players[$i],
                ]);
            }
        }
    }

    private function loadIntBets(): void
    {
        $numericData = [
            'Celkem gólů ČR' => [
                'Ondra' => 16, 'Táda' => 26, 'Martin' => 15, 'Pavel' => 20, 'Váca' => 30,
                'Kuba' => 15, 'Mééča' => 24, 'Honza S' => 14, 'Mates' => 28, 'Fanda' => 21,
            ],
            'Remízy v základní době' => [
                'Ondra' => 5, 'Táda' => 11, 'Martin' => 5, 'Pavel' => 3, 'Váca' => 6,
                'Kuba' => 5, 'Mééča' => 6, 'Honza S' => 5, 'Mates' => 7, 'Fanda' => 0,
            ],
            'Trestné minuty Gudase' => [
                'Ondra' => 8, 'Táda' => 24, 'Martin' => 6, 'Pavel' => 12, 'Váca' => 14,
                'Kuba' => 4, 'Mééča' => 16, 'Honza S' => 6, 'Mates' => 22, 'Fanda' => 6,
            ],
        ];

        foreach ($numericData as $ruleName => $userValues) {
            $rule = SpecialBetRuleStory::get($ruleName);

            foreach ($userValues as $username => $value) {
                SpecialBetFactory::createOne([
                    'user' => UserStory::get($username),
                    'rule' => $rule,
                    'intValue' => $value,
                ]);
            }
        }
    }
}
