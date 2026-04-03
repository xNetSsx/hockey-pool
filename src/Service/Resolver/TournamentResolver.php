<?php

declare(strict_types=1);

namespace App\Service\Resolver;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\SpecialBetRule;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Enum\BetScoringType;
use App\Repository\GameRepository;
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
        private GameRepository $gameRepository,
        private PointEntryRepository $pointEntryRepository,
        private PredictionRepository $predictionRepository,
        private SpecialBetRepository $specialBetRepository,
        private SpecialBetRuleRepository $specialBetRuleRepository,
        private RuleSetRepository $ruleSetRepository,
    ) {
    }

    public function resolveMatch(Game $game): void
    {
        $this->pointEntryManager->removeAll($this->pointEntryRepository->findByGame($game));

        $ruleSet = $this->ruleSetRepository->findByTournament($game->getTournament());
        /** @var list<Prediction> $predictions */
        $predictions = $this->predictionRepository->findBy(['game' => $game]);

        $entries = $this->matchPointResolver->resolve($game, $predictions, $ruleSet);

        if (count($entries) > 0) {
            $this->pointEntryManager->saveAll($entries);
        }
    }

    public function resolveSpecialBets(Tournament $tournament): void
    {
        $this->resolveAllSpecialBets($tournament);
    }

    public function recalculateAll(Tournament $tournament): void
    {
        $this->resolveAllMatches($tournament);
        $this->resolveAllSpecialBets($tournament);
    }

    private function resolveAllMatches(Tournament $tournament): void
    {
        $ruleSet = $this->ruleSetRepository->findByTournament($tournament);

        $finishedGames = $this->gameRepository->findFinishedByTournament($tournament);

        // Batch-fetch all existing game point entries and predictions in two queries
        $existingEntriesByGame = $this->pointEntryRepository->findGameEntriesByTournamentIndexedByGameId($tournament);
        $predictionsByGame = $this->predictionRepository->findByGamesIndexedByGameId($finishedGames);

        $allOldEntries = [];
        $allNewEntries = [];

        foreach ($finishedGames as $game) {
            $gameId = (int) $game->getId();
            array_push($allOldEntries, ...($existingEntriesByGame[$gameId] ?? []));

            $predictions = $predictionsByGame[$gameId] ?? [];
            $entries = $this->matchPointResolver->resolve($game, $predictions, $ruleSet);
            array_push($allNewEntries, ...$entries);
        }

        $this->pointEntryManager->replaceAll($allOldEntries, $allNewEntries);
    }

    private function resolveAllSpecialBets(Tournament $tournament): void
    {
        $oldEntries = $this->pointEntryRepository->findSpecialBetEntries($tournament);

        $rules = $this->specialBetRuleRepository->findByTournament($tournament);
        $specialBets = $this->specialBetRepository->findByTournamentIndexedByRule($tournament);

        [$podiumTeams, $anyMatchPool] = $this->buildScoringContext($rules);

        $newEntries = [];

        foreach ($rules as $rule) {
            if (!$rule->hasActualValue()) {
                continue;
            }

            $ruleId = $rule->getId();
            assert($ruleId !== null);
            $bets = $specialBets[$ruleId] ?? [];
            array_push($newEntries, ...$this->specialBetResolver->resolve($rule, $bets, $podiumTeams, $anyMatchPool));
        }

        $this->pointEntryManager->replaceAll($oldEntries, $newEntries);
    }

    /**
     * @param list<SpecialBetRule> $rules
     * @return array{list<Team>, list<string>}
     */
    private function buildScoringContext(array $rules): array
    {
        $podiumTeams = [];
        $anyMatchPool = [];

        foreach ($rules as $rule) {
            if (!$rule->hasActualValue()) {
                continue;
            }

            if ($rule->getScoringType() === BetScoringType::Podium) {
                $podiumTeams[] = $rule->getActualTeamValue();
            } elseif ($rule->getScoringType() === BetScoringType::AnyMatch) {
                $anyMatchPool[] = $rule->getActualStringValue();
            }
        }

        return [$podiumTeams, $anyMatchPool];
    }
}
