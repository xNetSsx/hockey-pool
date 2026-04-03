<?php

declare(strict_types=1);

namespace App\Service\Resolver\SpecialBet;

use App\Entity\SpecialBetRule;
use App\Enum\BetScoringType;

final class ClosestScoringResolver implements SpecialBetScoringResolverInterface
{
    use PointEntryCreatorTrait;

    public function supports(BetScoringType $type): bool
    {
        return $type === BetScoringType::Closest;
    }

    public function resolve(SpecialBetRule $rule, array $bets, array $podiumTeams, array $anyMatchPool): array
    {
        $actual = $rule->getActualIntValue();
        assert($actual !== null);

        $distances = [];
        foreach ($bets as $bet) {
            if ($bet->getIntValue() === null) {
                continue;
            }

            $distances[] = [
                'bet' => $bet,
                'distance' => abs($bet->getIntValue() - $actual)
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
}
