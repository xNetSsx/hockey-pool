<?php

declare(strict_types=1);

namespace App\Service\Resolver;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Enum\BetScoringType;
use App\Repository\PointEntryRepository;
use App\Repository\PredictionRepository;
use App\Repository\RuleSetRepository;
use App\Repository\SpecialBetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Service\Manager\PointEntryManager;

final readonly class TournamentResolver
{
    public function __construct(
        private MatchPointResolver $matchPointResolver,
        private SpecialBetResolver $specialBetResolver,
        private PointEntryManager $pointEntryManager,
        private PointEntryRepository $pointEntryRepository,
        private PredictionRepository $predictionRepository,
        private SpecialBetRepository $specialBetRepository,
        private SpecialBetRuleRepository $specialBetRuleRepository,
        private RuleSetRepository $ruleSetRepository,
    ) {
    }

    /**
     * Resolves points for a single match. Idempotent.
     */
    public function resolveMatch(Game $game): void
    {
        $this->pointEntryManager->removeAll($this->pointEntryRepository->findByGame($game));

        $ruleSet = $this->ruleSetRepository->findByTournament($game->getTournament());
        $predictions = $this->predictionRepository->findByGame($game);
        $entries = $this->matchPointResolver->resolve($game, $predictions, $ruleSet);

        if (count($entries) > 0) {
            $this->pointEntryManager->saveAll($entries);
        }
    }

    /**
     * Resolves all special bet points for a tournament. Idempotent.
     */
    public function resolveSpecialBets(Tournament $tournament): void
    {
        $this->resolveAllSpecialBets($tournament);
    }

    /**
     * Re-resolves all scoring for a tournament.
     */
    public function recalculateAll(Tournament $tournament): void
    {
        $this->resolveAllMatches($tournament);
        $this->resolveAllSpecialBets($tournament);
    }

    private function resolveAllMatches(Tournament $tournament): void
    {
        $ruleSet = $this->ruleSetRepository->findByTournament($tournament);

        foreach ($tournament->getMatches() as $game) {
            if (!$game->isFinished()) {
                continue;
            }

            $this->pointEntryManager->removeAll($this->pointEntryRepository->findByGame($game));

            $predictions = $this->predictionRepository->findByGame($game);
            $entries = $this->matchPointResolver->resolve($game, $predictions, $ruleSet);

            if (count($entries) > 0) {
                $this->pointEntryManager->saveAll($entries);
            }
        }
    }

    private function resolveAllSpecialBets(Tournament $tournament): void
    {
        $this->pointEntryManager->removeAll($this->pointEntryRepository->findSpecialBetEntries($tournament));

        $rules = $this->specialBetRuleRepository->findByTournament($tournament);

        // Collect podium teams for podium scoring
        $podiumTeams = [];
        // Collect string values for any_match scoring
        $anyMatchPool = [];
        foreach ($rules as $rule) {
            if ($rule->getScoringType() === BetScoringType::Podium && $rule->getActualTeamValue() !== null) {
                $podiumTeams[] = $rule->getActualTeamValue();
            }
            if ($rule->getScoringType() === BetScoringType::AnyMatch && $rule->getActualStringValue() !== null) {
                $anyMatchPool[] = $rule->getActualStringValue();
            }
        }

        $allEntries = [];

        foreach ($rules as $rule) {
            if (!$rule->hasActualValue()) {
                continue;
            }

            $bets = $this->specialBetRepository->findByRule($rule);
            $entries = $this->specialBetResolver->resolve($rule, $bets, $podiumTeams, $anyMatchPool);
            array_push($allEntries, ...$entries);
        }

        if (count($allEntries) > 0) {
            $this->pointEntryManager->saveAll($allEntries);
        }
    }
}
