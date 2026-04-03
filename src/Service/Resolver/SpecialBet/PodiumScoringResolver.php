<?php

declare(strict_types=1);

namespace App\Service\Resolver\SpecialBet;

use App\Entity\Team;
use App\Entity\SpecialBetRule;
use App\Enum\BetScoringType;

final class PodiumScoringResolver implements SpecialBetScoringResolverInterface
{
    use PointEntryCreatorTrait;

    public function supports(BetScoringType $type): bool
    {
        return $type === BetScoringType::Podium;
    }

    public function resolve(SpecialBetRule $rule, array $bets, array $podiumTeams, array $anyMatchPool): array
    {
        $actualTeam = $rule->getActualTeamValue();
        assert($actualTeam !== null);

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
}
