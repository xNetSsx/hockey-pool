<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Resolver;

use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Service\Resolver\SpecialBetResolver;
use PHPUnit\Framework\TestCase;

class SpecialBetResolverTest extends TestCase
{
    private SpecialBetResolver $resolver;
    private Tournament $tournament;
    private int $idCounter = 1;

    protected function setUp(): void
    {
        $this->resolver = new SpecialBetResolver();
        $this->idCounter = 1;
        $this->tournament = $this->createStub(Tournament::class);
    }

    /** Exact match: team. */
    public function testExactMatchTeamCorrect(): void
    {
        $team = $this->stubTeam();
        $rule = $this->createRule(BetValueType::Team, BetScoringType::ExactMatch, 3.0);
        $rule->setActualTeamValue($team);

        $bet = $this->createBet($rule);
        $bet->setTeamValue($team);

        $entries = $this->resolver->resolve($rule, [$bet]);

        self::assertCount(1, $entries);
        self::assertSame(3.0, $entries[0]->getPoints());
    }

    public function testExactMatchTeamWrong(): void
    {
        $rule = $this->createRule(BetValueType::Team, BetScoringType::ExactMatch, 3.0);
        $rule->setActualTeamValue($this->stubTeam());

        $bet = $this->createBet($rule);
        $bet->setTeamValue($this->stubTeam()); // different team

        $entries = $this->resolver->resolve($rule, [$bet]);

        self::assertCount(0, $entries);
    }

    public function testExactMatchMultipleWinners(): void
    {
        $team = $this->stubTeam();
        $rule = $this->createRule(BetValueType::Team, BetScoringType::ExactMatch, 3.0);
        $rule->setActualTeamValue($team);

        $bet1 = $this->createBet($rule);
        $bet1->setTeamValue($team);
        $bet2 = $this->createBet($rule);
        $bet2->setTeamValue($team);

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2]);

        self::assertCount(2, $entries);
    }

    /** Exact match: string. */
    public function testExactMatchStringCorrect(): void
    {
        $rule = $this->createRule(BetValueType::String, BetScoringType::ExactMatch, 2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('Pastrňák');

        $entries = $this->resolver->resolve($rule, [$bet]);

        self::assertCount(1, $entries);
        self::assertSame(2.0, $entries[0]->getPoints());
    }

    public function testExactMatchStringWrong(): void
    {
        $rule = $this->createRule(BetValueType::String, BetScoringType::ExactMatch, 2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('Hertl');

        self::assertCount(0, $this->resolver->resolve($rule, [$bet]));
    }

    /** Closest: integer. */
    public function testClosestExactWinner(): void
    {
        $rule = $this->createRule(BetValueType::Integer, BetScoringType::Closest, 2.0);
        $rule->setActualIntValue(15);

        $bet1 = $this->createBet($rule);
        $bet1->setIntValue(15); // exact
        $bet2 = $this->createBet($rule);
        $bet2->setIntValue(20); // off by 5

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2]);

        self::assertCount(1, $entries);
        self::assertSame(2.0, $entries[0]->getPoints());
        self::assertStringContainsString('přesně', $entries[0]->getReason());
    }

    public function testClosestNonExactWinner(): void
    {
        $rule = $this->createRule(BetValueType::Integer, BetScoringType::Closest, 2.0);
        $rule->setActualIntValue(15);

        $bet1 = $this->createBet($rule);
        $bet1->setIntValue(14); // off by 1
        $bet2 = $this->createBet($rule);
        $bet2->setIntValue(20); // off by 5

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2]);

        self::assertCount(1, $entries);
        self::assertStringContainsString('nejblíž', $entries[0]->getReason());
    }

    public function testClosestMultipleTie(): void
    {
        $rule = $this->createRule(BetValueType::Integer, BetScoringType::Closest, 2.0);
        $rule->setActualIntValue(15);

        $bet1 = $this->createBet($rule);
        $bet1->setIntValue(14); // off by 1
        $bet2 = $this->createBet($rule);
        $bet2->setIntValue(16); // off by 1 (same distance)
        $bet3 = $this->createBet($rule);
        $bet3->setIntValue(20); // off by 5

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2, $bet3]);

        // Both bet1 and bet2 win (distance 1), bet3 loses
        self::assertCount(2, $entries);
    }

    /** Edge cases. */
    public function testNoActualValueReturnsEmpty(): void
    {
        $rule = $this->createRule(BetValueType::Integer, BetScoringType::Closest, 2.0);
        // no actual value set

        $bet = $this->createBet($rule);
        $bet->setIntValue(10);

        self::assertCount(0, $this->resolver->resolve($rule, [$bet]));
    }

    public function testNoBetsReturnsEmpty(): void
    {
        $rule = $this->createRule(BetValueType::Integer, BetScoringType::Closest, 2.0);
        $rule->setActualIntValue(15);

        self::assertCount(0, $this->resolver->resolve($rule, []));
    }

    public function testNullBetValueSkipped(): void
    {
        $rule = $this->createRule(BetValueType::Team, BetScoringType::ExactMatch, 3.0);
        $rule->setActualTeamValue($this->stubTeam());

        $bet = $this->createBet($rule);
        // teamValue is null

        self::assertCount(0, $this->resolver->resolve($rule, [$bet]));
    }

    /** Helpers. */
    private function createRule(BetValueType $valueType, BetScoringType $scoringType, float $points): SpecialBetRule
    {
        $rule = new SpecialBetRule();
        $rule->setTournament($this->tournament);
        $rule->setName('Test rule');
        $rule->setValueType($valueType);
        $rule->setScoringType($scoringType);
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

    private function stubTeam(): Team
    {
        $team = $this->createStub(Team::class);
        $team->method('getId')->willReturn($this->idCounter++);

        return $team;
    }
}
