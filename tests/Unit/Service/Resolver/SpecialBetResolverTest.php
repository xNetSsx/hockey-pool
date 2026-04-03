<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Resolver;

use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Service\Resolver\SpecialBet\SpecialBetScoringResolverInterface;
use App\Service\Resolver\SpecialBetResolver;
use PHPUnit\Framework\TestCase;

class SpecialBetResolverTest extends TestCase
{
    private Tournament $tournament;
    private int $idCounter = 1;

    protected function setUp(): void
    {
        $this->idCounter = 1;
        $this->tournament = $this->createStub(Tournament::class);
    }

    public function testDelegatesToSupportingResolver(): void
    {
        $rule = $this->createRule();
        $bet = $this->createBet($rule);

        $inner = $this->createMock(SpecialBetScoringResolverInterface::class);
        $inner->method('supports')->willReturn(true);
        $inner->expects(self::once())
            ->method('resolve')
            ->with($rule, [$bet], [], [])
            ->willReturn([]);

        $resolver = new SpecialBetResolver([$inner]);
        $resolver->resolve($rule, [$bet]);
    }

    public function testSkipsResolverThatDoesNotSupport(): void
    {
        $rule = $this->createRule();
        $bet = $this->createBet($rule);

        $unsupported = $this->createMock(SpecialBetScoringResolverInterface::class);
        $unsupported->method('supports')->willReturn(false);
        $unsupported->expects(self::never())->method('resolve');

        $supported = $this->createMock(SpecialBetScoringResolverInterface::class);
        $supported->method('supports')->willReturn(true);
        $supported->expects(self::once())->method('resolve')->willReturn([]);

        $resolver = new SpecialBetResolver([$unsupported, $supported]);
        $resolver->resolve($rule, [$bet]);
    }

    public function testReturnsEmptyWhenNoBets(): void
    {
        $rule = $this->createRule();

        $inner = $this->createMock(SpecialBetScoringResolverInterface::class);
        $inner->expects(self::never())->method('resolve');

        $resolver = new SpecialBetResolver([$inner]);
        self::assertCount(0, $resolver->resolve($rule, []));
    }

    public function testReturnsEmptyWhenNoResolverSupports(): void
    {
        $rule = $this->createRule();
        $bet = $this->createBet($rule);

        $inner = $this->createMock(SpecialBetScoringResolverInterface::class);
        $inner->method('supports')->willReturn(false);

        $resolver = new SpecialBetResolver([$inner]);
        self::assertCount(0, $resolver->resolve($rule, [$bet]));
    }

    private function createRule(): SpecialBetRule
    {
        $rule = new SpecialBetRule();
        $rule->setTournament($this->tournament);
        $rule->setName('Test rule');
        $rule->setValueType(BetValueType::Integer);
        $rule->setScoringType(BetScoringType::Closest);
        $rule->setPoints(2.0);

        return $rule;
    }

    private function createBet(SpecialBetRule $rule): SpecialBet
    {
        $user = $this->createStub(User::class);
        $user->method('getId')->willReturn($this->idCounter++);

        $bet = new SpecialBet();
        $bet->setUser($user);
        $bet->setRule($rule);

        return $bet;
    }
}
