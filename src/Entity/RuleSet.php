<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RuleSetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Configurable scoring rules per tournament.
 * Allows adjusting point values without code changes.
 */
#[ORM\Entity(repositoryClass: RuleSetRepository::class)]
#[ORM\Table(name: 'rule_set')]
class RuleSet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Tournament::class)]
    #[ORM\JoinColumn(nullable: false, unique: true, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private Tournament $tournament;

    #[ORM\Column(type: 'float', options: ['default' => 1.0])]
    #[Assert\Positive]
    private float $winnerBasePoints = 1.0;

    #[ORM\Column(type: 'float', options: ['default' => 0.25])]
    #[Assert\PositiveOrZero]
    private float $wrongOpponentBonus = 0.25;

    #[ORM\Column(type: 'float', options: ['default' => 2.0])]
    #[Assert\PositiveOrZero]
    private float $exactScoreBonus = 2.0;

    /**
     * @var array<string|int, int|float>
     */
    #[ORM\Column(type: 'json')]
    private array $prizes = ['1' => 300, '2' => 150, '3' => 50];

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

    public function getWinnerBasePoints(): float
    {
        return $this->winnerBasePoints;
    }

    public function setWinnerBasePoints(float $winnerBasePoints): self
    {
        $this->winnerBasePoints = $winnerBasePoints;

        return $this;
    }

    public function getWrongOpponentBonus(): float
    {
        return $this->wrongOpponentBonus;
    }

    public function setWrongOpponentBonus(float $wrongOpponentBonus): self
    {
        $this->wrongOpponentBonus = $wrongOpponentBonus;

        return $this;
    }

    public function getExactScoreBonus(): float
    {
        return $this->exactScoreBonus;
    }

    public function setExactScoreBonus(float $exactScoreBonus): self
    {
        $this->exactScoreBonus = $exactScoreBonus;

        return $this;
    }

    /** @return array<string|int, int|float> */
    public function getPrizes(): array
    {
        return $this->prizes;
    }

    /** @param array<string|int, int|float> $prizes */
    public function setPrizes(array $prizes): self
    {
        $this->prizes = $prizes;

        return $this;
    }
}
