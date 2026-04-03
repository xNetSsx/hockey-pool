<?php

declare(strict_types=1);

namespace App\Service\Resolver\SpecialBet;

use App\Entity\SpecialBetRule;
use App\Enum\BetScoringType;

final class AnyMatchScoringResolver implements SpecialBetScoringResolverInterface
{
    use PointEntryCreatorTrait;

    public function supports(BetScoringType $type): bool
    {
        return $type === BetScoringType::AnyMatch;
    }

    public function resolve(SpecialBetRule $rule, array $bets, array $podiumTeams, array $anyMatchPool): array
    {
        $entries = [];
        foreach ($bets as $bet) {
            $betValue = $bet->getStringValue();
            if (null === $betValue) {
                continue;
            }

            if (in_array($betValue, $anyMatchPool, true)) {
                $entries[] = $this->createEntry(
                    $bet,
                    $rule,
                    $rule->getPoints(),
                    sprintf('%s — správně (%s)', $rule->getName(), $betValue)
                );
            }
        }

        return $entries;
    }
}
