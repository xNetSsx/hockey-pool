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
use App\Service\Resolver\SpecialBet\PodiumScoringResolver;
use PHPUnit\Framework\TestCase;

class PodiumScoringResolverTest extends TestCase
{
    private PodiumScoringResolver $resolver;
    private Tournament $tournament;
    private int $idCounter = 1;

    protected function setUp(): void
    {
        $this->resolver = new PodiumScoringResolver();
        $this->idCounter = 1;
        $this->tournament = $this->createStub(Tournament::class);
    }

    public function testExactPositionGetsFullPoints(): void
    {
        [$gold, $silver, $bronze] = [$this->stubTeam(), $this->stubTeam(), $this->stubTeam()];
        $rule = $this->createRule($gold, 3.0);

        $bet = $this->createBet($rule);
        $bet->setTeamValue($gold);

        $entries = $this->resolver->resolve($rule, [$bet], [$gold, $silver, $bronze], []);

        self::assertCount(1, $entries);
        self::assertSame(3.0, $entries[0]->getPoints());
        self::assertStringContainsString('správná pozice', $entries[0]->getReason());
    }

    public function testInPodiumButWrongPositionGetsOnePoint(): void
    {
        [$gold, $silver, $bronze] = [$this->stubTeam(), $this->stubTeam(), $this->stubTeam()];
        $rule = $this->createRule($gold, 3.0);

        $bet = $this->createBet($rule);
        $bet->setTeamValue($silver); // in podium but wrong position

        $entries = $this->resolver->resolve($rule, [$bet], [$gold, $silver, $bronze], []);

        self::assertCount(1, $entries);
        self::assertSame(1.0, $entries[0]->getPoints());
        self::assertStringContainsString('top 3', $entries[0]->getReason());
    }

    public function testNotInPodiumGetsNothing(): void
    {
        [$gold, $silver, $bronze] = [$this->stubTeam(), $this->stubTeam(), $this->stubTeam()];
        $outside = $this->stubTeam();
        $rule = $this->createRule($gold, 3.0);

        $bet = $this->createBet($rule);
        $bet->setTeamValue($outside);

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [$gold, $silver, $bronze], []));
    }

    public function testNullBetTeamValueSkipped(): void
    {
        [$gold, $silver, $bronze] = [$this->stubTeam(), $this->stubTeam(), $this->stubTeam()];
        $rule = $this->createRule($gold, 3.0);

        $bet = $this->createBet($rule);
        // teamValue is null

        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [$gold, $silver, $bronze], []));
    }

    public function testMixedBetsAcrossPodiumPositions(): void
    {
        [$gold, $silver, $bronze] = [$this->stubTeam(), $this->stubTeam(), $this->stubTeam()];
        $outside = $this->stubTeam();
        $rule = $this->createRule($gold, 3.0); // gold is the actual position

        $betExact = $this->createBet($rule);
        $betExact->setTeamValue($gold);
        $betPodium = $this->createBet($rule);
        $betPodium->setTeamValue($silver);
        $betWrong = $this->createBet($rule);
        $betWrong->setTeamValue($outside);

        $entries = $this->resolver->resolve($rule, [$betExact, $betPodium, $betWrong], [$gold, $silver, $bronze], []);

        self::assertCount(2, $entries);
        self::assertSame(3.0, $entries[0]->getPoints()); // exact
        self::assertSame(1.0, $entries[1]->getPoints()); // in podium
    }

    public function testEmptyPodiumTeamsNoPointsAwarded(): void
    {
        $gold = $this->stubTeam();
        $rule = $this->createRule($gold, 3.0);

        $bet = $this->createBet($rule);
        $bet->setTeamValue($gold);

        // podiumTeams is empty — gold is not in the list
        self::assertCount(0, $this->resolver->resolve($rule, [$bet], [], []));
    }

    public function testSupportsOnlyPodium(): void
    {
        self::assertTrue($this->resolver->supports(BetScoringType::Podium));
        self::assertFalse($this->resolver->supports(BetScoringType::ExactMatch));
        self::assertFalse($this->resolver->supports(BetScoringType::Closest));
        self::assertFalse($this->resolver->supports(BetScoringType::AnyMatch));
    }

    private function createRule(Team $actualTeam, float $points): SpecialBetRule
    {
        $rule = new SpecialBetRule();
        $rule->setTournament($this->tournament);
        $rule->setName('Test rule');
        $rule->setValueType(BetValueType::Team);
        $rule->setScoringType(BetScoringType::Podium);
        $rule->setPoints($points);
        $rule->setActualTeamValue($actualTeam);

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
