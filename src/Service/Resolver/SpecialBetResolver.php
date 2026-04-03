<?php

declare(strict_types=1);

namespace App\Service\Resolver;

use App\Entity\PointEntry;
use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Team;
use App\Service\Resolver\SpecialBet\SpecialBetScoringResolverInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class SpecialBetResolver
{
    /** @param iterable<SpecialBetScoringResolverInterface> $resolvers */
    public function __construct(
        #[TaggedIterator('app.special_bet_scoring_resolver')]
        private readonly iterable $resolvers,
    ) {
    }

    /**
     * Resolves all bets for a single rule.
     *
     * @param list<SpecialBet> $bets
     * @param list<Team> $podiumTeams
     * @param list<string> $anyMatchPool
     * @return list<PointEntry>
     */
    public function resolve(SpecialBetRule $rule, array $bets, array $podiumTeams = [], array $anyMatchPool = []): array
    {
        if (0 === count($bets)) {
            return [];
        }

        foreach ($this->resolvers as $resolver) {
            if ($resolver->supports($rule->getScoringType())) {
                return $resolver->resolve($rule, $bets, $podiumTeams, $anyMatchPool);
            }
        }

        return [];
    }
}
