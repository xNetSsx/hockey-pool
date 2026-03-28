<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\PredictionFactory;
use Zenstruck\Foundry\Story;

final class PredictionStory extends Story
{
    public function build(): void
    {
        $matchData = GameStory::getMatchData();

        foreach (self::getPredictionData() as $matchIndex => $userPredictions) {
            $home = $matchData[$matchIndex]['home'];
            $away = $matchData[$matchIndex]['away'];
            $game = GameStory::get(GameStory::key($matchIndex, $home, $away));

            foreach ($userPredictions as $username => $scores) {
                $user = UserStory::get($username);

                PredictionFactory::createOne([
                    'user' => $user,
                    'game' => $game,
                    'homeScore' => $scores[0],
                    'awayScore' => $scores[1],
                ]);
            }
        }
    }

    /**
     * @return array<int, array<string, array{int, int}>>
     */
    public static function getPredictionData(): array
    {
        return [
            0 => [
                'Ondra' => [1, 3], 'Táda' => [1, 4], 'Martin' => [1, 4], 'Pavel' => [2, 5],
                'Váca' => [2, 4], 'Kuba' => [1, 4], 'Mééča' => [1, 4], 'Honza S' => [1, 4],
                'Mates' => [1, 3], 'Fanda' => [2, 1],
            ],
            1 => [
                'Ondra' => [7, 0], 'Táda' => [8, 0], 'Martin' => [7, 1], 'Pavel' => [5, 1],
                'Váca' => [6, 1], 'Kuba' => [10, 0], 'Mééča' => [6, 0], 'Honza S' => [5, 0],
                'Mates' => [4, 0], 'Fanda' => [5, 1],
            ],
            2 => [
                'Ondra' => [5, 1], 'Táda' => [4, 2], 'Martin' => [5, 1], 'Pavel' => [5, 2],
                'Váca' => [5, 1], 'Kuba' => [4, 1], 'Mééča' => [3, 1], 'Honza S' => [4, 1],
                'Mates' => [4, 1], 'Fanda' => [4, 1],
            ],
            3 => [
                'Ondra' => [1, 3], 'Táda' => [3, 2], 'Martin' => [2, 4], 'Pavel' => [2, 3],
                'Váca' => [3, 4], 'Kuba' => [3, 2], 'Mééča' => [2, 3], 'Honza S' => [2, 3],
                'Mates' => [2, 1], 'Fanda' => [2, 3],
            ],
            4 => [
                'Ondra' => [2, 4], 'Táda' => [1, 4], 'Martin' => [2, 6], 'Pavel' => [1, 4],
                'Váca' => [2, 5], 'Kuba' => [1, 4], 'Mééča' => [2, 5], 'Honza S' => [1, 4],
                'Mates' => [0, 4], 'Fanda' => [3, 2],
            ],
            5 => [
                'Ondra' => [4, 2], 'Táda' => [3, 2], 'Martin' => [4, 1], 'Pavel' => [3, 1],
                'Váca' => [3, 2], 'Kuba' => [3, 2], 'Mééča' => [3, 2], 'Honza S' => [3, 2],
                'Mates' => [4, 2], 'Fanda' => [2, 3],
            ],
            6 => [
                'Ondra' => [3, 1], 'Táda' => [2, 4], 'Martin' => [1, 4], 'Pavel' => [2, 4],
                'Váca' => [1, 4], 'Kuba' => [2, 3], 'Mééča' => [3, 2], 'Honza S' => [1, 2],
                'Mates' => [2, 3], 'Fanda' => [5, 4],
            ],
            7 => [
                'Ondra' => [0, 2], 'Táda' => [2, 3], 'Martin' => [1, 4], 'Pavel' => [2, 4],
                'Váca' => [2, 3], 'Kuba' => [0, 3], 'Mééča' => [1, 4], 'Honza S' => [1, 3],
                'Mates' => [1, 5], 'Fanda' => [1, 4],
            ],
            8 => [
                'Ondra' => [2, 5], 'Táda' => [1, 5], 'Martin' => [1, 4], 'Pavel' => [1, 7],
                'Váca' => [1, 6], 'Kuba' => [2, 5], 'Mééča' => [1, 5], 'Honza S' => [1, 4],
                'Mates' => [1, 3], 'Fanda' => [2, 6],
            ],
            9 => [
                'Ondra' => [4, 3], 'Táda' => [4, 1], 'Martin' => [4, 2], 'Pavel' => [5, 1],
                'Váca' => [5, 2], 'Kuba' => [4, 3], 'Mééča' => [3, 1], 'Honza S' => [3, 2],
                'Mates' => [4, 0], 'Fanda' => [3, 5],
            ],
            10 => [
                'Ondra' => [3, 2], 'Táda' => [4, 2], 'Martin' => [4, 2], 'Pavel' => [4, 2],
                'Váca' => [4, 2], 'Kuba' => [0, 0], 'Mééča' => [4, 2], 'Honza S' => [4, 2],
                'Mates' => [2, 4], 'Fanda' => [4, 2],
            ],
            11 => [
                'Ondra' => [4, 2], 'Táda' => [3, 4], 'Martin' => [4, 2], 'Pavel' => [3, 2],
                'Váca' => [2, 1], 'Kuba' => [0, 0], 'Mééča' => [3, 2], 'Honza S' => [3, 2],
                'Mates' => [3, 1], 'Fanda' => [2, 3],
            ],
            12 => [
                'Ondra' => [5, 1], 'Táda' => [5, 1], 'Martin' => [4, 0], 'Pavel' => [4, 1],
                'Váca' => [5, 2], 'Kuba' => [5, 1], 'Mééča' => [6, 1], 'Honza S' => [4, 1],
                'Mates' => [3, 0], 'Fanda' => [5, 1],
            ],
            13 => [
                'Ondra' => [6, 1], 'Táda' => [6, 2], 'Martin' => [5, 1], 'Pavel' => [6, 0],
                'Váca' => [6, 1], 'Kuba' => [5, 1], 'Mééča' => [4, 1], 'Honza S' => [6, 1],
                'Mates' => [5, 0], 'Fanda' => [4, 1],
            ],
            14 => [
                'Ondra' => [1, 3], 'Táda' => [2, 4], 'Martin' => [4, 2], 'Pavel' => [2, 4],
                'Váca' => [2, 4], 'Kuba' => [1, 2], 'Mééča' => [2, 3], 'Honza S' => [1, 3],
                'Mates' => [1, 3], 'Fanda' => [1, 3],
            ],
            15 => [
                'Ondra' => [6, 1], 'Táda' => [8, 1], 'Martin' => [8, 1], 'Pavel' => [8, 0],
                'Váca' => [8, 1], 'Kuba' => [7, 0], 'Mééča' => [7, 0], 'Honza S' => [7, 0],
                'Mates' => [10, 0], 'Fanda' => [8, 2],
            ],
            16 => [
                'Ondra' => [1, 3], 'Táda' => [2, 3], 'Martin' => [2, 4], 'Pavel' => [2, 4],
                'Váca' => [1, 3], 'Kuba' => [4, 3], 'Mééča' => [3, 1], 'Honza S' => [1, 4],
                'Mates' => [2, 3], 'Fanda' => [3, 2],
            ],
            17 => [
                'Ondra' => [5, 2], 'Táda' => [5, 3], 'Martin' => [4, 2], 'Pavel' => [5, 1],
                'Váca' => [6, 2], 'Kuba' => [5, 3], 'Mééča' => [4, 2], 'Honza S' => [5, 2],
                'Mates' => [5, 2], 'Fanda' => [7, 1],
            ],
            18 => [
                'Ondra' => [5, 0], 'Táda' => [6, 1], 'Martin' => [6, 1], 'Pavel' => [6, 1],
                'Váca' => [4, 1], 'Kuba' => [5, 0], 'Mééča' => [8, 0], 'Honza S' => [6, 0],
                'Mates' => [5, 1], 'Fanda' => [6, 1],
            ],
            19 => [
                'Ondra' => [4, 2], 'Táda' => [4, 2], 'Martin' => [4, 1], 'Pavel' => [5, 2],
                'Váca' => [3, 2], 'Kuba' => [4, 2], 'Mééča' => [5, 1], 'Honza S' => [3, 1],
                'Mates' => [3, 1], 'Fanda' => [4, 2],
            ],
            20 => [
                'Ondra' => [5, 1], 'Táda' => [4, 0], 'Martin' => [4, 2], 'Pavel' => [4, 2],
                'Váca' => [5, 3], 'Kuba' => [4, 2], 'Mééča' => [5, 2], 'Honza S' => [3, 1],
                'Mates' => [3, 1], 'Fanda' => [3, 1],
            ],
            21 => [
                'Ondra' => [4, 3], 'Táda' => [7, 2], 'Martin' => [6, 1], 'Pavel' => [4, 2],
                'Váca' => [6, 4], 'Kuba' => [5, 2], 'Mééča' => [6, 1], 'Honza S' => [5, 2],
                'Mates' => [4, 2], 'Fanda' => [4, 3],
            ],
            22 => [
                'Ondra' => [4, 3], 'Táda' => [4, 2], 'Martin' => [4, 2], 'Pavel' => [3, 2],
                'Váca' => [4, 2], 'Kuba' => [3, 4], 'Mééča' => [2, 3], 'Honza S' => [4, 2],
                'Mates' => [4, 2], 'Fanda' => [3, 4],
            ],
            23 => [
                'Ondra' => [4, 2], 'Táda' => [1, 2], 'Martin' => [5, 2], 'Pavel' => [4, 1],
                'Váca' => [5, 2], 'Kuba' => [3, 5], 'Mééča' => [4, 2], 'Honza S' => [5, 1],
                'Mates' => [2, 3], 'Fanda' => [3, 4],
            ],
            24 => [
                'Ondra' => [3, 2], 'Táda' => [3, 4], 'Martin' => [3, 1], 'Pavel' => [3, 1],
                'Váca' => [4, 1], 'Kuba' => [3, 2], 'Mééča' => [2, 4], 'Honza S' => [4, 2],
                'Mates' => [2, 0], 'Fanda' => [2, 5],
            ],
            25 => [
                'Ondra' => [3, 2], 'Táda' => [2, 3], 'Martin' => [3, 4], 'Pavel' => [4, 2],
                'Váca' => [4, 3], 'Kuba' => [4, 3], 'Mééča' => [5, 3], 'Honza S' => [4, 3],
                'Mates' => [4, 2], 'Fanda' => [1, 3],
            ],
            26 => [
                'Ondra' => [3, 1], 'Táda' => [4, 1], 'Martin' => [4, 2], 'Pavel' => [4, 2],
                'Váca' => [4, 2], 'Kuba' => [3, 1], 'Mééča' => [3, 1], 'Honza S' => [4, 3],
                'Mates' => [4, 2], 'Fanda' => [2, 3],
            ],
            27 => [
                'Ondra' => [3, 2], 'Táda' => [2, 3], 'Martin' => [4, 2], 'Pavel' => [3, 1],
                'Váca' => [2, 4], 'Kuba' => [4, 3], 'Mééča' => [3, 1], 'Honza S' => [4, 3],
                'Mates' => [1, 3], 'Fanda' => [2, 3],
            ],
            28 => [
                'Ondra' => [1, 4], 'Táda' => [3, 2], 'Martin' => [2, 5], 'Pavel' => [2, 4],
                'Váca' => [3, 2], 'Kuba' => [2, 4], 'Mééča' => [4, 3], 'Honza S' => [1, 4],
                'Mates' => [2, 1], 'Fanda' => [6, 1],
            ],
            29 => [
                'Ondra' => [2, 1], 'Táda' => [1, 3], 'Martin' => [2, 4], 'Pavel' => [4, 3],
                'Váca' => [4, 3], 'Kuba' => [2, 3], 'Mééča' => [6, 5], 'Honza S' => [3, 2],
                'Mates' => [3, 1], 'Fanda' => [3, 4],
            ],
        ];
    }
}
