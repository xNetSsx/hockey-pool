<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PredictionTest extends TestCase
{
    private int $idCounter = 1;

    /** isExactScore tests */
    public function testIsExactScoreReturnsTrueWhenScoresMatchExactly(): void
    {
        $game = $this->createGame(3, 1);
        $prediction = $this->createPrediction($game, 3, 1);

        self::assertTrue($prediction->isExactScore($game));
    }

    public function testIsExactScoreReturnsFalseWhenScoresDiffer(): void
    {
        $game = $this->createGame(3, 1);
        $prediction = $this->createPrediction($game, 2, 1);

        self::assertFalse($prediction->isExactScore($game));
    }

    public function testIsExactScoreReturnsFalseWhenGameScoresAreNull(): void
    {
        $game = $this->createGame(null, null);
        $prediction = $this->createPrediction($game, 2, 1);

        self::assertFalse($prediction->isExactScore($game));
    }

    /** isCorrectWinner tests */
    public function testIsCorrectWinnerReturnsTrueForCorrectHomeWinPrediction(): void
    {
        $game = $this->createGame(3, 1);
        $prediction = $this->createPrediction($game, 2, 0);

        self::assertTrue($prediction->isCorrectWinner($game));
    }

    public function testIsCorrectWinnerReturnsTrueForCorrectAwayWinPrediction(): void
    {
        $game = $this->createGame(1, 3);
        $prediction = $this->createPrediction($game, 0, 2);

        self::assertTrue($prediction->isCorrectWinner($game));
    }

    public function testIsCorrectWinnerReturnsTrueWhenBothPredictDrawAndGameDraws(): void
    {
        $game = $this->createGame(2, 2);
        $prediction = $this->createPrediction($game, 1, 1);

        self::assertTrue($prediction->isCorrectWinner($game));
    }

    public function testIsCorrectWinnerReturnsFalseWhenPredictionIsWrong(): void
    {
        $game = $this->createGame(3, 1);
        $prediction = $this->createPrediction($game, 0, 2);

        self::assertFalse($prediction->isCorrectWinner($game));
    }

    /** getPredictedWinner tests */
    public function testGetPredictedWinnerReturnsHomeTeamForHomeWinPrediction(): void
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

        $prediction = $this->createPrediction($game, 3, 1);

        self::assertSame($homeTeam, $prediction->getPredictedWinner());
    }

    public function testGetPredictedWinnerReturnsAwayTeamForAwayWinPrediction(): void
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

        $prediction = $this->createPrediction($game, 1, 3);

        self::assertSame($awayTeam, $prediction->getPredictedWinner());
    }

    public function testGetPredictedWinnerReturnsNullForDrawPrediction(): void
    {
        $game = $this->createGame(null, null);
        $prediction = $this->createPrediction($game, 1, 1);

        self::assertNull($prediction->getPredictedWinner());
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

    private function stubTeam(): Team
    {
        $team = $this->createStub(Team::class);
        $team->method('getId')->willReturn($this->idCounter++);

        return $team;
    }

    private function createPrediction(Game $game, int $homeScore, int $awayScore): Prediction
    {
        $prediction = new Prediction();
        $prediction->setGame($game);
        $prediction->setHomeScore($homeScore);
        $prediction->setAwayScore($awayScore);

        return $prediction;
    }
}
