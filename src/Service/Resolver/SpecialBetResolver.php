<?php

declare(strict_types=1);

namespace App\Service\Resolver;

use App\Entity\PointEntry;
use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Team;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;

/**
 * Pure scoring logic for special bets. Driven by SpecialBetRule configuration.
 *
 * Scoring types:
 *  - exact_match: every user whose value matches the actual gets points
 *  - closest: only the user(s) with the smallest distance to actual get points (integer only)
 *  - podium: 1 point if team is anywhere in podium, +1 if exact position match
 */
final class SpecialBetResolver
{
    /**
     * Resolves all bets for a single rule.
     *
     * @param SpecialBetRule $rule
     * @param list<SpecialBet> $bets All user bets for this rule
     * @param list<Team> $podiumTeams All actual podium teams (for podium scoring only)
     * @param list<string> $anyMatchPool All actual string values for any_match scoring
     * @return list<PointEntry>
     */
    public function resolve(SpecialBetRule $rule, array $bets, array $podiumTeams = [], array $anyMatchPool = []): array
    {
        if (!$rule->hasActualValue() || 0 === count($bets)) {
            return [];
        }

        return match ($rule->getScoringType()) {
            BetScoringType::ExactMatch => $this->resolveExactMatch($rule, $bets),
            BetScoringType::Closest => $this->resolveClosest($rule, $bets),
            BetScoringType::Podium => $this->resolvePodium($rule, $bets, $podiumTeams),
            BetScoringType::AnyMatch => $this->resolveAnyMatch($rule, $bets, $anyMatchPool),
        };
    }

    /**
     * Every user whose answer matches the actual value gets points.
     *
     * @param SpecialBetRule $rule
     * @param list<SpecialBet> $bets
     * @return list<PointEntry>
     */
    private function resolveExactMatch(SpecialBetRule $rule, array $bets): array
    {
        $entries = [];

        foreach ($bets as $bet) {
            if ($this->isMatch($rule, $bet)) {
                $entries[] = $this->createEntry($bet, $rule, $rule->getPoints(), sprintf('%s — správně', $rule->getName()));
            }
        }

        return $entries;
    }

    /**
     * Only the user(s) closest to the actual integer value get points.
     *
     * @param SpecialBetRule $rule
     * @param list<SpecialBet> $bets
     * @return list<PointEntry>
     */
    private function resolveClosest(SpecialBetRule $rule, array $bets): array
    {
        if ($rule->getValueType() !== BetValueType::Integer) {
            return [];
        }

        $actual = $rule->getActualIntValue();

        if (null === $actual) {
            return [];
        }

        $distances = [];
        foreach ($bets as $bet) {
            if ($bet->getIntValue() === null) {
                continue;
            }

            $distances[] = [
                'bet' => $bet,
                'distance' => abs($bet->getIntValue() - $actual),
            ];
        }

        if (0 === count($distances)) {
            return [];
        }

        $minDistance = min(array_column($distances, 'distance'));

        $entries = [];
        foreach ($distances as $row) {
            if ($row['distance'] === $minDistance) {
                $label = $minDistance === 0
                    ? sprintf('%s — přesně (%d)', $rule->getName(), $actual)
                    : sprintf('%s — nejblíž (tip: %d, skutečnost: %d)', $rule->getName(), $row['bet']->getIntValue(), $actual);

                $entries[] = $this->createEntry($row['bet'], $rule, $rule->getPoints(), $label);
            }
        }

        return $entries;
    }

    /**
     * Podium scoring: 1 point if team is anywhere in top 3, +1 if exact position.
     *
     * @param SpecialBetRule $rule
     * @param list<SpecialBet> $bets
     * @param list<Team> $podiumTeams All actual podium teams
     * @return list<PointEntry>
     */
    private function resolvePodium(SpecialBetRule $rule, array $bets, array $podiumTeams): array
    {
        $actualTeam = $rule->getActualTeamValue();

        if (null === $actualTeam) {
            return [];
        }

        $podiumTeamIds = array_map(static fn (Team $t) => $t->getId(), $podiumTeams);

        $entries = [];
        foreach ($bets as $bet) {
            $betTeam = $bet->getTeamValue();
            if (null === $betTeam) {
                continue;
            }

            $betTeamId = $betTeam->getId();
            $isExactPosition = $betTeamId === $actualTeam->getId();
            $isInPodium = in_array($betTeamId, $podiumTeamIds, true);

            if (!$isInPodium) {
                continue;
            }

            if ($isExactPosition) {
                $entries[] = $this->createEntry($bet, $rule, $rule->getPoints(), sprintf('%s — správná pozice', $rule->getName()));
            } else {
                $entries[] = $this->createEntry($bet, $rule, 1.0, sprintf('%s — tým v top 3', $rule->getName()));
            }
        }

        return $entries;
    }

    /**
     * AnyMatch: award points if the bet value appears anywhere in the pool of actual values.
     *
     * @param SpecialBetRule $rule
     * @param list<SpecialBet> $bets
     * @param list<string> $anyMatchPool
     * @return list<PointEntry>
     */
    private function resolveAnyMatch(SpecialBetRule $rule, array $bets, array $anyMatchPool): array
    {
        $entries = [];
        foreach ($bets as $bet) {
            $betValue = $bet->getStringValue();
            if (null === $betValue) {
                continue;
            }

            if (in_array($betValue, $anyMatchPool, true)) {
                $entries[] = $this->createEntry($bet, $rule, $rule->getPoints(), sprintf('%s — správně (%s)', $rule->getName(), $betValue));
            }
        }

        return $entries;
    }

    private function isMatch(SpecialBetRule $rule, SpecialBet $bet): bool
    {
        return match ($rule->getValueType()) {
            BetValueType::Team => $bet->getTeamValue() !== null
                && $rule->getActualTeamValue() !== null
                && $bet->getTeamValue()->getId() === $rule->getActualTeamValue()->getId(),
            BetValueType::String => $bet->getStringValue() !== null
                && $rule->getActualStringValue() !== null
                && $bet->getStringValue() === $rule->getActualStringValue(),
            BetValueType::Integer => $bet->getIntValue() !== null
                && $rule->getActualIntValue() !== null
                && $bet->getIntValue() === $rule->getActualIntValue(),
        };
    }

    private function createEntry(SpecialBet $bet, SpecialBetRule $rule, float $points, string $reason): PointEntry
    {
        return (new PointEntry())
            ->setUser($bet->getUser())
            ->setTournament($rule->getTournament())
            ->setSpecialBetRule($rule)
            ->setPoints($points)
            ->setReason($reason);
    }
}
