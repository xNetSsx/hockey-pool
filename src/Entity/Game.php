<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TournamentPhase;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ORM\Table(name: '`match`')]
#[ORM\Index(columns: ['tournament_id'], name: 'idx_match_tournament')]
#[ORM\Index(columns: ['home_team_id'], name: 'idx_match_home_team')]
#[ORM\Index(columns: ['away_team_id'], name: 'idx_match_away_team')]
#[ORM\Index(columns: ['played_at'], name: 'idx_match_played_at')]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tournament::class, inversedBy: 'matches')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private Tournament $tournament;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private Team $homeTeam;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private Team $awayTeam;

    #[ORM\Column(length: 20, enumType: TournamentPhase::class)]
    #[Assert\NotNull]
    private TournamentPhase $phase;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    private DateTimeImmutable $playedAt;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $homeScore = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $awayScore = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isFinished = false;

    public static function create(
        Tournament $tournament,
        TournamentPhase $phase,
        Team $homeTeam,
        Team $awayTeam,
        DateTimeImmutable $playedAt,
    ): self {
        $game = new self();
        $game->tournament = $tournament;
        $game->phase = $phase;
        $game->homeTeam = $homeTeam;
        $game->awayTeam = $awayTeam;
        $game->playedAt = $playedAt;

        return $game;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getPhase(): TournamentPhase
    {
        return $this->phase;
    }

    public function setPhase(TournamentPhase $phase): self
    {
        $this->phase = $phase;

        return $this;
    }

    public function getPlayedAt(): DateTimeImmutable
    {
        return $this->playedAt;
    }

    public function setPlayedAt(DateTimeImmutable $playedAt): self
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(?int $homeScore): self
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(?int $awayScore): self
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): self
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    public function getWinner(): ?Team
    {
        if ($this->homeScore === null || $this->awayScore === null) {
            return null;
        }

        if ($this->homeScore > $this->awayScore) {
            return $this->homeTeam;
        }

        if ($this->awayScore > $this->homeScore) {
            return $this->awayTeam;
        }

        return null;
    }

}
