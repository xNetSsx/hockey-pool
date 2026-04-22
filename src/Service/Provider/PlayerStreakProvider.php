<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Prediction;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Repository\PredictionRepository;

/**
 * Computes hot/cold streaks for players based on their recent predictions.
 *
 * Hot streak: 3+ consecutive finished games where the player scored points (correct winner or exact).
 * Cold hand: 3+ consecutive finished games where the player scored 0 points.
 */
final readonly class PlayerStreakProvider
{
    public function __construct(
        private GameRepository $gameRepository,
        private PredictionRepository $predictionRepository,
    ) {
    }

    /**
     * @return array<int, array{streak: int, hot: bool, cold: bool}> userId => streak info
     */
    public function getStreaks(Tournament $tournament): array
    {
        $finishedGames = $this->gameRepository->findFinishedByTournament($tournament);

        if ([] === $finishedGames) {
            return [];
        }

        $rawByGame = $this->predictionRepository->findByGamesIndexedByGameId($finishedGames);


        /** @var array<int, array<int, Prediction>> $predictionsByGame */
        $predictionsByGame = [];
        foreach ($rawByGame as $gameId => $predictions) {
            foreach ($predictions as $prediction) {
                $userId = $prediction->getUser()->getId();
                if (null !== $userId) {
                    $predictionsByGame[$gameId][$userId] = $prediction;
                }
            }
        }

        /** @var array<int, true> $userIds */
        $userIds = [];
        foreach ($predictionsByGame as $predictions) {
            foreach ($predictions as $userId => $prediction) {
                $userIds[$userId] = true;
            }
        }

        $reversedGames = array_reverse($finishedGames);
        $result = [];

        foreach (array_keys($userIds) as $userId) {
            $consecutive = 0;
            $isHit = null; // null = not started, true = scoring, false = missing

            foreach ($reversedGames as $game) {
                $gameId = $game->getId();
                if (null === $gameId) {
                    continue;
                }

                $prediction = $predictionsByGame[$gameId][$userId] ?? null;

                if (null === $prediction) {
                    // No prediction = miss
                    $scored = false;
                } else {
                    $scored = $prediction->isCorrectWinner($game) || $prediction->isExactScore($game);
                }

                if (null === $isHit) {
                    $isHit = $scored;
                    $consecutive = 1;
                } elseif ($isHit === $scored) {
                    $consecutive++;
                } else {
                    break;
                }
            }

            $result[$userId] = [
                'streak' => $consecutive,
                'hot' => $isHit === true && $consecutive >= 2,
                'cold' => $isHit === false && $consecutive >= 2,
            ];
        }

        return $result;
    }
}
