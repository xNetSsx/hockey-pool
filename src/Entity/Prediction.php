<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PredictionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PredictionRepository::class)]
#[ORM\Table(name: 'prediction')]
#[ORM\UniqueConstraint(name: 'uniq_prediction_user_game', columns: ['user_id', 'game_id'])]
#[ORM\Index(name: 'idx_prediction_user', columns: ['user_id'])]
#[ORM\Index(name: 'idx_prediction_game', columns: ['game_id'])]
#[UniqueEntity(fields: ['user', 'game'])]
class Prediction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Game::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private Game $game;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[Assert\NotEqualTo(propertyPath: 'awayScore', message: 'Remíza není povolena — vždy musí být vítěz.')]
    private int $homeScore;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private int $awayScore;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getHomeScore(): int
    {
        return $this->homeScore;
    }

    public function setHomeScore(int $homeScore): self
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): int
    {
        return $this->awayScore;
    }

    public function setAwayScore(int $awayScore): self
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPredictedWinner(): ?Team
    {
        if ($this->homeScore > $this->awayScore) {
            return $this->game->getHomeTeam();
        }

        if ($this->awayScore > $this->homeScore) {
            return $this->game->getAwayTeam();
        }

        return null;
    }

    public function isExactScore(Game $game): bool
    {
        if ($game->getHomeScore() === null || $game->getAwayScore() === null) {
            return false;
        }

        return $this->homeScore === $game->getHomeScore()
            && $this->awayScore === $game->getAwayScore();
    }

    public function isCorrectWinner(Game $game): bool
    {
        $actualWinner = $game->getWinner();
        $predictedWinner = $this->getPredictedWinner();

        if (null === $actualWinner && null === $predictedWinner) {
            return $game->getHomeScore() !== null;
        }

        if (null === $actualWinner || null === $predictedWinner) {
            return false;
        }

        return $actualWinner->getId() === $predictedWinner->getId();
    }
}
