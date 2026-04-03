<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Resolver\SpecialBet;

use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Service\Resolver\SpecialBet\ClosestScoringResolver;
use PHPUnit\Framework\TestCase;

class ClosestScoringResolverTest extends TestCase
{
    private ClosestScoringResolver $resolver;
    private Tournament $tournament;
    private int $idCounter = 1;

    protected function setUp(): void
    {
        $this->resolver = new ClosestScoringResolver();
        $this->idCounter = 1;
        $this->tournament = $this->createStub(Tournament::class);
    }

    public function testExactWinnerGetsPointsWithExactLabel(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualIntValue(15);

        $bet1 = $this->createBet($rule);
        $bet1->setIntValue(15); // exact
        $bet2 = $this->createBet($rule);
        $bet2->setIntValue(20); // off by 5

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2], [], []);

        self::assertCount(1, $entries);
        self::assertSame(2.0, $entries[0]->getPoints());
        self::assertStringContainsString('přesně', $entries[0]->getReason());
    }

    public function testNonExactClosestWinsWithClosestLabel(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualIntValue(15);

        $bet1 = $this->createBet($rule);
        $bet1->setIntValue(14); // off by 1
        $bet2 = $this->createBet($rule);
        $bet2->setIntValue(20); // off by 5

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2], [], []);

        self::assertCount(1, $entries);
        self::assertSame(2.0, $entries[0]->getPoints());
        self::assertStringContainsString('nejblíž', $entries[0]->getReason());
    }

    public function testTieAwardsAllClosest(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualIntValue(15);

        $bet1 = $this->createBet($rule);
        $bet1->setIntValue(14); // off by 1
        $bet2 = $this->createBet($rule);
        $bet2->setIntValue(16); // off by 1
        $bet3 = $this->createBet($rule);
        $bet3->setIntValue(20); // off by 5

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2, $bet3], [], []);

        self::assertCount(2, $entries);
    }

    public function testNullBetValueSkipped(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualIntValue(15);

        $bet1 = $this->createBet($rule);
        // intValue is null
        $bet2 = $this->createBet($rule);
        $bet2->setIntValue(15);

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2], [], []);

        self::assertCount(1, $entries);
    }

    public function testAllNullBetValuesReturnsEmpty(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualIntValue(15);

        $bet = $this->createBet($rule);
        // intValue is null

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testNonIntegerValueTypeReturnsEmpty(): void
    {
        $rule = new SpecialBetRule();
        $rule->setTournament($this->tournament);
        $rule->setName('Test rule');
        $rule->setValueType(BetValueType::String);
        $rule->setScoringType(BetScoringType::Closest);
        $rule->setPoints(2.0);
        $rule->setActualStringValue('foo');

        $bet = $this->createBet($rule);
        $bet->setStringValue('foo');

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testSupportsOnlyClosest(): void
    {
        self::assertTrue($this->resolver->supports(BetScoringType::Closest));
        self::assertFalse($this->resolver->supports(BetScoringType::ExactMatch));
        self::assertFalse($this->resolver->supports(BetScoringType::Podium));
        self::assertFalse($this->resolver->supports(BetScoringType::AnyMatch));
    }

    private function createRule(float $points): SpecialBetRule
    {
        $rule = new SpecialBetRule();
        $rule->setTournament($this->tournament);
        $rule->setName('Test rule');
        $rule->setValueType(BetValueType::Integer);
        $rule->setScoringType(BetScoringType::Closest);
        $rule->setPoints($points);

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
