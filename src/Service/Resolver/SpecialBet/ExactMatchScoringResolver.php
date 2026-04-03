<?php

declare(strict_types=1);

namespace App\Service\Resolver\SpecialBet;

use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;

final class ExactMatchScoringResolver implements SpecialBetScoringResolverInterface
{
    use PointEntryCreatorTrait;

    public function supports(BetScoringType $type): bool
    {
        return $type === BetScoringType::ExactMatch;
    }

    public function resolve(SpecialBetRule $rule, array $bets, array $podiumTeams, array $anyMatchPool): array
    {
        $entries = [];

        foreach ($bets as $bet) {
            if ($this->isMatch($rule, $bet)) {
                $entries[] = $this->createEntry($bet, $rule, $rule->getPoints(), sprintf('%s — správně', $rule->getName()));
            }
        }

        return $entries;
    }

    private function isMatch(SpecialBetRule $rule, SpecialBet $bet): bool
    {
        $valueType = $rule->getValueType();

        if ($valueType === BetValueType::Team) {
            $actualTeam = $rule->getActualTeamValue();
            assert($actualTeam !== null);

            return $bet->getTeamValue() !== null
                && $bet->getTeamValue()->getId() === $actualTeam->getId();
        }

        return match ($valueType) {
            BetValueType::String => $bet->getStringValue() !== null
                && $bet->getStringValue() === $rule->getActualStringValue(),
            BetValueType::Integer => $bet->getIntValue() !== null
                && $bet->getIntValue() === $rule->getActualIntValue(),
        };
    }
}
