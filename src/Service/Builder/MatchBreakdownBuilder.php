<?php

declare(strict_types=1);

namespace App\Service\Builder;

use App\Entity\Game;
use App\Entity\PointEntry;
use App\Entity\Prediction;
use App\Entity\User;
use App\Enum\PointCategory;
use App\Repository\PointEntryRepository;
use App\Repository\PredictionRepository;
use App\Repository\TournamentParticipantRepository;
use App\Service\Resolver\MatchPointResolver;

/**
 * Builds per-user point breakdown for a single match.
 */
final readonly class MatchBreakdownBuilder
{
    public function __construct(
        private PointEntryRepository $pointEntryRepository,
        private PredictionRepository $predictionRepository,
        private TournamentParticipantRepository $tournamentParticipantRepository,
    ) {
    }

    /**
     * @return list<array{
     *     user: User,
     *     prediction: Prediction,
     *     correctWinner: bool,
     *     exactScore: bool,
     *     basePoints: float,
     *     opponentBonus: float,
     *     exactBonus: float,
     *     totalPoints: float,
     * }>
     */
    public function build(Game $game): array
    {
        /** @var list<Prediction> $predictions */
        $predictions = $this->predictionRepository->findBy(['game' => $game]);

        // Index real predictions by user ID for fast lookup
        /** @var array<int, true> $submittedUserIds */
        $submittedUserIds = [];
        foreach ($predictions as $prediction) {
            $submittedUserIds[$prediction->getUser()->getId()] = true;
        }

        // Append synthetic 0:0 predictions for participants who didn't submit — never persisted
        $participants = $this->tournamentParticipantRepository->findByTournament($game->getTournament());
        foreach ($participants as $participant) {
            $userId = $participant->getUser()->getId();
            if (isset($submittedUserIds[$userId])) {
                continue;
            }

            $predictions[] = (new Prediction())
                ->setUser($participant->getUser())
                ->setGame($game)
                ->setHomeScore(0)
                ->setAwayScore(0);
        }

        $pointEntries = $this->pointEntryRepository->findByGame($game);

        /** @var array<int, list<PointEntry>> $entriesByUser */
        $entriesByUser = [];
        foreach ($pointEntries as $entry) {
            $entriesByUser[$entry->getUser()->getId()][] = $entry;
        }

        $breakdown = [];

        foreach ($predictions as $prediction) {
            $userId = $prediction->getUser()->getId();
            $entries = $entriesByUser[$userId] ?? [];

            $basePoints = 0.0;
            $opponentBonus = 0.0;
            $exactBonus = 0.0;

            foreach ($entries as $entry) {
                $category = $entry->getCategory();
                $reason = $entry->getReason();
                if (PointCategory::CorrectWinner === $category || (null === $category && MatchPointResolver::REASON_CORRECT_WINNER === $reason)) {
                    $basePoints = $entry->getPoints();
                } elseif (PointCategory::OpponentBonus === $category || (null === $category && str_starts_with($reason, 'Wrong opponent bonus'))) {
                    $opponentBonus = $entry->getPoints();
                } elseif (PointCategory::ExactScoreBonus === $category || (null === $category && MatchPointResolver::REASON_EXACT_SCORE_BONUS === $reason)) {
                    $exactBonus = $entry->getPoints();
                }
            }

            $breakdown[] = [
                'user' => $prediction->getUser(),
                'prediction' => $prediction,
                'correctWinner' => $game->isFinished() && $prediction->isCorrectWinner($game),
                'exactScore' => $game->isFinished() && $prediction->isExactScore($game),
                'basePoints' => $basePoints,
                'opponentBonus' => $opponentBonus,
                'exactBonus' => $exactBonus,
                'totalPoints' => $basePoints + $opponentBonus + $exactBonus,
            ];
        }

        usort($breakdown, static fn (array $a, array $b) => $b['totalPoints'] <=> $a['totalPoints']);

        return $breakdown;
    }
}
