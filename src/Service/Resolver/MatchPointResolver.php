<?php

declare(strict_types=1);

namespace App\Service\Resolver;

use App\Entity\Game;
use App\Entity\PointEntry;
use App\Entity\Prediction;
use App\Entity\RuleSet;

/**
 * Pure scoring logic for match predictions. No persistence — returns PointEntry objects.
 *
 * Scoring values come from the tournament's RuleSet (falls back to defaults if none).
 */
final class MatchPointResolver
{
    public const string REASON_CORRECT_WINNER = 'Correct winner';
    public const string REASON_EXACT_SCORE_BONUS = 'Exact score bonus';

    private const float DEFAULT_WINNER_BASE = 1.0;
    private const float DEFAULT_OPPONENT_BONUS = 0.25;
    private const float DEFAULT_EXACT_BONUS = 2.0;

    /**
     * @param Game $game
     * @param list<Prediction> $predictions
     * @param RuleSet|null $ruleSet
     *
     * @return list<PointEntry>
     */
    public function resolve(Game $game, array $predictions, ?RuleSet $ruleSet = null): array
    {
        if (!$game->isFinished() || $game->getHomeScore() === null || $game->getAwayScore() === null) {
            return [];
        }

        if (0 === count($predictions)) {
            return [];
        }

        $basePoints = $ruleSet?->getWinnerBasePoints() ?? self::DEFAULT_WINNER_BASE;
        $opponentBonus = $ruleSet?->getWrongOpponentBonus() ?? self::DEFAULT_OPPONENT_BONUS;
        $exactBonus = $ruleSet?->getExactScoreBonus() ?? self::DEFAULT_EXACT_BONUS;

        $correctPredictions = [];
        $wrongCount = 0;

        foreach ($predictions as $prediction) {
            if ($prediction->isCorrectWinner($game)) {
                $correctPredictions[] = $prediction;
            } else {
                $wrongCount++;
            }
        }

        $entries = [];

        foreach ($correctPredictions as $prediction) {
            $user = $prediction->getUser();
            $tournament = $game->getTournament();

            $entries[] = (new PointEntry())
                ->setUser($user)
                ->setTournament($tournament)
                ->setGame($game)
                ->setPoints($basePoints)
                ->setReason(self::REASON_CORRECT_WINNER);

            if ($wrongCount > 0) {
                $bonus = $wrongCount * $opponentBonus;
                $entries[] = (new PointEntry())
                    ->setUser($user)
                    ->setTournament($tournament)
                    ->setGame($game)
                    ->setPoints($bonus)
                    ->setReason(
                        sprintf('Wrong opponent bonus (%s × %d)', number_format($opponentBonus, 2), $wrongCount),
                    );
            }

            if ($prediction->isExactScore($game)) {
                $entries[] = (new PointEntry())
                    ->setUser($user)
                    ->setTournament($tournament)
                    ->setGame($game)
                    ->setPoints($exactBonus)
                    ->setReason(self::REASON_EXACT_SCORE_BONUS);
            }
        }

        return $entries;
    }
}
