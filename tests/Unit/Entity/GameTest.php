<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private int $idCounter = 1;

    public function testGetWinnerReturnsHomeTeamWhenHomeScoreIsHigher(): void
    {
        $homeTeam = $this->stubTeam();
        $awayTeam = $this->stubTeam();
        $tournament = $this->createStub(Tournament::class);

        $game = new Game();
        $game->setTournament($tournament);
        $game->setHomeTeam($homeTeam);
        $game->setAwayTeam($awayTeam);
        $game->setPhase(TournamentPhase::GroupStage);
        $game->setPlayedAt(new DateTimeImmutable());
        $game->setHomeScore(3);
        $game->setAwayScore(1);

        self::assertSame($homeTeam, $game->getWinner());
    }

    public function testGetWinnerReturnsAwayTeamWhenAwayScoreIsHigher(): void
    {
        $homeTeam = $this->stubTeam();
        $awayTeam = $this->stubTeam();
        $tournament = $this->createStub(Tournament::class);

        $game = new Game();
        $game->setTournament($tournament);
        $game->setHomeTeam($homeTeam);
        $game->setAwayTeam($awayTeam);
        $game->setPhase(TournamentPhase::GroupStage);
        $game->setPlayedAt(new DateTimeImmutable());
        $game->setHomeScore(1);
        $game->setAwayScore(3);

        self::assertSame($awayTeam, $game->getWinner());
    }

    public function testGetWinnerReturnsNullWhenScoresAreEqual(): void
    {
        $game = $this->createGame(2, 2);

        self::assertNull($game->getWinner());
    }

    public function testGetWinnerReturnsNullWhenScoresAreNotSet(): void
    {
        $game = $this->createGame(null, null);

        self::assertNull($game->getWinner());
    }

    private function stubTeam(): Team
    {
        $team = $this->createStub(Team::class);
        $team->method('getId')->willReturn($this->idCounter++);

        return $team;
    }

    private function createGame(?int $homeScore, ?int $awayScore): Game
    {
        $tournament = $this->createStub(Tournament::class);

        $game = new Game();
        $game->setTournament($tournament);
        $game->setHomeTeam($this->stubTeam());
        $game->setAwayTeam($this->stubTeam());
        $game->setPhase(TournamentPhase::GroupStage);
        $game->setPlayedAt(new DateTimeImmutable());
        $game->setHomeScore($homeScore);
        $game->setAwayScore($awayScore);

        return $game;
    }
}
