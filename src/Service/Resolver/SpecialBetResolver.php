<?php

declare(strict_types=1);

namespace App\Service\Resolver;

use App\Entity\PointEntry;
use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;

/**
 * Pure scoring logic for special bets. Driven by SpecialBetRule configuration.
 *
 * Scoring types:
 *  - exact_match: every user whose value matches the actual gets points
 *  - closest: only the user(s) with the smallest distance to actual get points (integer only)
 */
final class SpecialBetResolver
{
    /**
     * Resolves all bets for a single rule.
     *
     * @param SpecialBetRule $rule
     * @param list<SpecialBet> $bets All user bets for this rule
     * @return list<PointEntry>
     */
    public function resolve(SpecialBetRule $rule, array $bets): array
    {
        if (!$rule->hasActualValue() || 0 === count($bets)) {
            return [];
        }

        return match ($rule->getScoringType()) {
            BetScoringType::ExactMatch => $this->resolveExactMatch($rule, $bets),
            BetScoringType::Closest => $this->resolveClosest($rule, $bets),
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
                $entries[] = $this->createEntry($bet, $rule, sprintf('%s — správně', $rule->getName()));
            }
        }

        return $entries;
    }

    /**
     * Only the user(s) closest to the actual integer value get points.
     * Multiple users can tie if they have the same distance.
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

        // Calculate distances
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

        // Find minimum distance
        $minDistance = min(array_column($distances, 'distance'));

        // Award points to all winners
        $entries = [];
        foreach ($distances as $row) {
            if ($row['distance'] === $minDistance) {
                $label = $minDistance === 0
                    ? sprintf('%s — přesně (%d)', $rule->getName(), $actual)
                    : sprintf('%s — nejblíž (tip: %d, skutečnost: %d)', $rule->getName(), $row['bet']->getIntValue(), $actual);

                $entries[] = $this->createEntry($row['bet'], $rule, $label);
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

    private function createEntry(SpecialBet $bet, SpecialBetRule $rule, string $reason): PointEntry
    {
        return (new PointEntry())
            ->setUser($bet->getUser())
            ->setTournament($rule->getTournament())
            ->setSpecialBetRule($rule)
            ->setPoints($rule->getPoints())
            ->setReason($reason);
    }
}
