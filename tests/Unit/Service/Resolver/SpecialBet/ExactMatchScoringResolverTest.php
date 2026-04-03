<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Resolver\SpecialBet;

use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Service\Resolver\SpecialBet\ExactMatchScoringResolver;
use PHPUnit\Framework\TestCase;

class ExactMatchScoringResolverTest extends TestCase
{
    private ExactMatchScoringResolver $resolver;
    private Tournament $tournament;
    private int $idCounter = 1;

    protected function setUp(): void
    {
        $this->resolver = new ExactMatchScoringResolver();
        $this->idCounter = 1;
        $this->tournament = $this->createStub(Tournament::class);
    }

    public function testTeamCorrect(): void
    {
        $team = $this->stubTeam();
        $rule = $this->createRule(BetValueType::Team, 3.0);
        $rule->setActualTeamValue($team);

        $bet = $this->createBet($rule);
        $bet->setTeamValue($team);

        $entries = $this->resolver->resolve($rule, [$bet], [], []);

        self::assertCount(1, $entries);
        self::assertSame(3.0, $entries[0]->getPoints());
    }

    public function testTeamWrong(): void
    {
        $rule = $this->createRule(BetValueType::Team, 3.0);
        $rule->setActualTeamValue($this->stubTeam());

        $bet = $this->createBet($rule);
        $bet->setTeamValue($this->stubTeam());

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testTeamNullBetValueSkipped(): void
    {
        $rule = $this->createRule(BetValueType::Team, 3.0);
        $rule->setActualTeamValue($this->stubTeam());

        $bet = $this->createBet($rule);
        // teamValue is null

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testMultipleWinners(): void
    {
        $team = $this->stubTeam();
        $rule = $this->createRule(BetValueType::Team, 3.0);
        $rule->setActualTeamValue($team);

        $bet1 = $this->createBet($rule);
        $bet1->setTeamValue($team);
        $bet2 = $this->createBet($rule);
        $bet2->setTeamValue($team);
        $bet3 = $this->createBet($rule);
        $bet3->setTeamValue($this->stubTeam()); // wrong

        $entries = $this->resolver->resolve($rule, [$bet1, $bet2, $bet3], [], []);

        self::assertCount(2, $entries);
    }

    public function testStringCorrect(): void
    {
        $rule = $this->createRule(BetValueType::String, 2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('Pastrňák');

        $entries = $this->resolver->resolve($rule, [$bet], [], []);

        self::assertCount(1, $entries);
        self::assertSame(2.0, $entries[0]->getPoints());
    }

    public function testStringWrong(): void
    {
        $rule = $this->createRule(BetValueType::String, 2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('Hertl');

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testStringCaseSensitive(): void
    {
        $rule = $this->createRule(BetValueType::String, 2.0);
        $rule->setActualStringValue('Pastrňák');

        $bet = $this->createBet($rule);
        $bet->setStringValue('pastrňák');

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testIntegerCorrect(): void
    {
        $rule = $this->createRule(BetValueType::Integer, 2.0);
        $rule->setActualIntValue(42);

        $bet = $this->createBet($rule);
        $bet->setIntValue(42);

        $entries = $this->resolver->resolve($rule, [$bet], [], []);

        self::assertCount(1, $entries);
        self::assertSame(2.0, $entries[0]->getPoints());
    }

    public function testIntegerWrong(): void
    {
        $rule = $this->createRule(BetValueType::Integer, 2.0);
        $rule->setActualIntValue(42);

        $bet = $this->createBet($rule);
        $bet->setIntValue(41);

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testSupportsOnlyExactMatch(): void
    {
        self::assertTrue($this->resolver->supports(BetScoringType::ExactMatch));
        self::assertFalse($this->resolver->supports(BetScoringType::Closest));
        self::assertFalse($this->resolver->supports(BetScoringType::Podium));
        self::assertFalse($this->resolver->supports(BetScoringType::AnyMatch));
    }

    private function createRule(BetValueType $valueType, float $points): SpecialBetRule
    {
        $rule = new SpecialBetRule();
        $rule->setTournament($this->tournament);
        $rule->setName('Test rule');
        $rule->setValueType($valueType);
        $rule->setScoringType(BetScoringType::ExactMatch);
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
