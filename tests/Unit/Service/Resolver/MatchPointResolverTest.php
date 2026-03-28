<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Resolver;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\RuleSet;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Entity\User;
use App\Enum\TournamentPhase;
use App\Service\Resolver\MatchPointResolver;
use DateTime;
use PHPUnit\Framework\TestCase;

class MatchPointResolverTest extends TestCase
{
    private MatchPointResolver $resolver;
    private int $idCounter = 1;

    protected function setUp(): void
    {
        $this->resolver = new MatchPointResolver();
        $this->idCounter = 1;
    }

    public function testReturnsEmptyForUnfinishedMatch(): void
    {
        $game = $this->createGame(null, null, false);
        $prediction = $this->createPrediction($game, 2, 1);

        $entries = $this->resolver->resolve($game, [$prediction]);

        self::assertCount(0, $entries);
    }

    public function testReturnsEmptyForNoPredictions(): void
    {
        $game = $this->createGame(3, 1, true);

        $entries = $this->resolver->resolve($game, []);

        self::assertCount(0, $entries);
    }

    public function testCorrectWinnerGetsBasePoint(): void
    {
        $game = $this->createGame(3, 1, true);
        $prediction = $this->createPrediction($game, 2, 0);

        $entries = $this->resolver->resolve($game, [$prediction]);

        self::assertCount(1, $entries);
        self::assertSame(1.0, $entries[0]->getPoints());
        self::assertSame('Correct winner', $entries[0]->getReason());
    }

    public function testWrongWinnerGetsNothing(): void
    {
        $game = $this->createGame(3, 1, true);
        $prediction = $this->createPrediction($game, 0, 2);

        $entries = $this->resolver->resolve($game, [$prediction]);

        self::assertCount(0, $entries);
    }

    public function testOpponentBonusCalculation(): void
    {
        $game = $this->createGame(3, 1, true);

        $correct = $this->createPrediction($game, 2, 0);
        $wrong1 = $this->createPrediction($game, 0, 2, $this->stubUser());
        $wrong2 = $this->createPrediction($game, 1, 3, $this->stubUser());

        $entries = $this->resolver->resolve($game, [$correct, $wrong1, $wrong2]);

        // base (1.0) + opponent bonus (2 × 0.25 = 0.5)
        self::assertCount(2, $entries);
        self::assertSame(1.0, $entries[0]->getPoints());
        self::assertSame(0.5, $entries[1]->getPoints());
        self::assertSame('Wrong opponent bonus (0.25 × 2)', $entries[1]->getReason());
    }

    public function testExactScoreBonus(): void
    {
        $game = $this->createGame(3, 1, true);
        $prediction = $this->createPrediction($game, 3, 1);

        $entries = $this->resolver->resolve($game, [$prediction]);

        // base (1.0) + exact (2.0)
        self::assertCount(2, $entries);
        self::assertSame(1.0, $entries[0]->getPoints());
        self::assertSame(2.0, $entries[1]->getPoints());
        self::assertSame('Exact score bonus', $entries[1]->getReason());
    }

    public function testAllRulesStack(): void
    {
        $game = $this->createGame(3, 1, true);

        $predictions = [];

        // User who gets exact score
        $exactUser = $this->stubUser();
        $predictions[] = $this->createPrediction($game, 3, 1, $exactUser);

        // 2 users who get winner right but not exact
        for ($i = 0; $i < 2; $i++) {
            $predictions[] = $this->createPrediction($game, 2, 0, $this->stubUser());
        }

        // 7 users who get it wrong
        for ($i = 0; $i < 7; $i++) {
            $predictions[] = $this->createPrediction($game, 0, 2, $this->stubUser());
        }

        $entries = $this->resolver->resolve($game, $predictions);

        // Filter entries for the exact user
        $exactEntries = array_values(array_filter(
            $entries,
            static fn ($e) => $e->getUser() === $exactUser,
        ));

        // base (1.0) + opponent (7 × 0.25 = 1.75) + exact (2.0) = 4.75
        self::assertCount(3, $exactEntries);

        $total = array_sum(array_map(static fn ($e) => $e->getPoints(), $exactEntries));
        self::assertSame(4.75, $total);
    }

    public function testDrawPredictionCorrectWhenMatchDraws(): void
    {
        $game = $this->createGame(2, 2, true);
        $prediction = $this->createPrediction($game, 1, 1);

        $entries = $this->resolver->resolve($game, [$prediction]);

        self::assertCount(1, $entries);
        self::assertSame(1.0, $entries[0]->getPoints());
    }

    public function testDrawPredictionWrongWhenMatchHasWinner(): void
    {
        $game = $this->createGame(3, 1, true);
        $prediction = $this->createPrediction($game, 2, 2);

        $entries = $this->resolver->resolve($game, [$prediction]);

        self::assertCount(0, $entries);
    }

    public function testEveryoneCorrectNoOpponentBonus(): void
    {
        $game = $this->createGame(3, 1, true);

        $predictions = [];
        for ($i = 0; $i < 5; $i++) {
            $predictions[] = $this->createPrediction($game, 4, 2, $this->stubUser());
        }

        $entries = $this->resolver->resolve($game, $predictions);

        // 5 users × 1 base point each, no opponent bonus
        self::assertCount(5, $entries);

        foreach ($entries as $entry) {
            self::assertSame(1.0, $entry->getPoints());
            self::assertSame('Correct winner', $entry->getReason());
        }
    }

    public function testOnlyOneCorrectGetsMaxOpponentBonus(): void
    {
        $game = $this->createGame(3, 1, true);

        $correctUser = $this->stubUser();
        $predictions = [$this->createPrediction($game, 2, 0, $correctUser)];

        for ($i = 0; $i < 9; $i++) {
            $predictions[] = $this->createPrediction($game, 0, 5, $this->stubUser());
        }

        $entries = $this->resolver->resolve($game, $predictions);

        // Only the correct user gets entries: base (1.0) + opponent (9 × 0.25 = 2.25)
        self::assertCount(2, $entries);

        $total = array_sum(array_map(static fn ($e) => $e->getPoints(), $entries));
        self::assertSame(3.25, $total);
    }

    public function testCustomRuleSetValues(): void
    {
        $game = $this->createGame(3, 1, true);

        $ruleSet = new RuleSet();
        $ruleSet->setTournament($game->getTournament());
        $ruleSet->setWinnerBasePoints(2.0);
        $ruleSet->setWrongOpponentBonus(0.5);
        $ruleSet->setExactScoreBonus(5.0);

        $correct = $this->createPrediction($game, 3, 1); // exact
        $wrong = $this->createPrediction($game, 0, 2, $this->stubUser());

        $entries = $this->resolver->resolve($game, [$correct, $wrong], $ruleSet);

        // base (2.0) + opponent (1 × 0.5 = 0.5) + exact (5.0) = 7.5
        self::assertCount(3, $entries);
        self::assertSame(2.0, $entries[0]->getPoints()); // base
        self::assertSame(0.5, $entries[1]->getPoints()); // opponent
        self::assertSame(5.0, $entries[2]->getPoints()); // exact
    }

    private function createGame(?int $homeScore, ?int $awayScore, bool $finished): Game
    {
        $tournament = $this->createStub(Tournament::class);
        $tournament->method('getName')->willReturn('Test');

        $game = new Game();
        $game->setTournament($tournament);
        $game->setHomeTeam($this->stubTeam());
        $game->setAwayTeam($this->stubTeam());
        $game->setPhase(TournamentPhase::GroupStage);
        $game->setPlayedAt(new DateTime());
        $game->setHomeScore($homeScore);
        $game->setAwayScore($awayScore);
        $game->setIsFinished($finished);

        return $game;
    }

    private function createPrediction(Game $game, int $homeScore, int $awayScore, ?User $user = null): Prediction
    {
        $prediction = new Prediction();
        $prediction->setUser($user ?? $this->stubUser());
        $prediction->setGame($game);
        $prediction->setHomeScore($homeScore);
        $prediction->setAwayScore($awayScore);

        return $prediction;
    }

    private function stubUser(): User
    {
        $user = $this->createStub(User::class);
        $user->method('getId')->willReturn($this->idCounter++);

        return $user;
    }

    private function stubTeam(): Team
    {
        $team = $this->createStub(Team::class);
        $team->method('getId')->willReturn($this->idCounter++);

        return $team;
    }
}
