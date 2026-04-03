<?php

declare(strict_types=1);

namespace App\Service\Resolver\SpecialBet;

use App\Entity\PointEntry;
use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Team;
use App\Enum\BetScoringType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.special_bet_scoring_resolver')]
interface SpecialBetScoringResolverInterface
{
    public function supports(BetScoringType $type): bool;

    /**
     * @param list<SpecialBet> $bets
     * @param list<Team> $podiumTeams
     * @param list<string> $anyMatchPool
     * @return list<PointEntry>
     */
    public function resolve(SpecialBetRule $rule, array $bets, array $podiumTeams, array $anyMatchPool): array;
}
