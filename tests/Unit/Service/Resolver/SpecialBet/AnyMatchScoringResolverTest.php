<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Resolver\SpecialBet;

use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Service\Resolver\SpecialBet\AnyMatchScoringResolver;
use PHPUnit\Framework\TestCase;

class AnyMatchScoringResolverTest extends TestCase
{
    private AnyMatchScoringResolver $resolver;
    private Tournament $tournament;
    private int $idCounter = 1;

    protected function setUp(): void
    {
        $this->resolver = new AnyMatchScoringResolver();
        $this->idCounter = 1;
        $this->tournament = $this->createStub(Tournament::class);
    }

    public function testValueInPoolGetsPoints(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('Pastrňák');

        $entries = $this->resolver->resolve($rule, [$bet], [], ['Pastrňák', 'Hertl']);

        self::assertCount(1, $entries);
        self::assertSame(2.0, $entries[0]->getPoints());
    }

    public function testValueNotInPoolGetsNothing(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('Kämpf');

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], ['Pastrňák', 'Hertl']));
    }

    public function testMultipleWinnersFromPool(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet1 = $this->createBet($rule);
        $bet1->setStringValue('Pastrňák');
        $bet2 = $this->createBet($rule);
        $bet2->setStringValue('Hertl');
        $bet3 = $this->createBet($rule);
        $bet3->setStringValue('Kämpf'); // not in pool

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2, $bet3], [], ['Pastrňák', 'Hertl']);

        self::assertCount(2, $entries);
    }

    public function testNullBetValueSkipped(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        // stringValue is null

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], ['Pastrňák']));
    }

    public function testEmptyPoolGetsNothing(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('Pastrňák');

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testMatchIsCaseSensitive(): void
    {
        $rule = $this->createRule(2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('pastrňák');

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], ['Pastrňák']));
    }

    public function testSupportsOnlyAnyMatch(): void
    {
        self::assertTrue($this->resolver->supports(BetScoringType::AnyMatch));
        self::assertFalse($this->resolver->supports(BetScoringType::ExactMatch));
        self::assertFalse($this->resolver->supports(BetScoringType::Closest));
        self::assertFalse($this->resolver->supports(BetScoringType::Podium));
    }

    private function createRule(float $points): SpecialBetRule
    {
        $rule = new SpecialBetRule();
        $rule->setTournament($this->tournament);
        $rule->setName('Test rule');
        $rule->setValueType(BetValueType::String);
        $rule->setScoringType(BetScoringType::AnyMatch);
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
