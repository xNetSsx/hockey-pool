<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\BetScoringType;
use App\Enum\BetValueType;
use App\Repository\SpecialBetRuleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defines a single special bet for a tournament.
 *
 * Examples: "Zlatá medaile" (team, exact_match, 3pts),
 *           "Celkem gólů ČR" (integer, closest, 2pts)
 *
 * The actual result is stored here too — admin fills it in after the tournament.
 */
#[ORM\Entity(repositoryClass: SpecialBetRuleRepository::class)]
#[ORM\Table(name: 'special_bet_rule')]
#[ORM\Index(columns: ['tournament_id'], name: 'idx_special_bet_rule_tournament')]
class SpecialBetRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tournament::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private Tournament $tournament;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name;

    #[ORM\Column(length: 20, enumType: BetValueType::class)]
    #[Assert\NotNull]
    private BetValueType $valueType;

    #[ORM\Column(length: 20, enumType: BetScoringType::class)]
    #[Assert\NotNull]
    private BetScoringType $scoringType;

    #[ORM\Column(type: 'float')]
    #[Assert\Positive]
    private float $points;

    #[ORM\Column]
    private int $sortOrder = 0;

    // ── Actual result (filled by admin after tournament) ──

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Team $actualTeamValue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $actualStringValue = null;

    #[ORM\Column(nullable: true)]
    private ?int $actualIntValue = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isMedalRule = false;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValueType(): BetValueType
    {
        return $this->valueType;
    }

    public function setValueType(BetValueType $valueType): self
    {
        $this->valueType = $valueType;

        return $this;
    }

    public function getScoringType(): BetScoringType
    {
        return $this->scoringType;
    }

    public function setScoringType(BetScoringType $scoringType): self
    {
        $this->scoringType = $scoringType;

        return $this;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function setPoints(float $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getActualTeamValue(): ?Team
    {
        return $this->actualTeamValue;
    }

    public function setActualTeamValue(?Team $actualTeamValue): self
    {
        $this->actualTeamValue = $actualTeamValue;

        return $this;
    }

    public function getActualStringValue(): ?string
    {
        return $this->actualStringValue;
    }

    public function setActualStringValue(?string $actualStringValue): self
    {
        $this->actualStringValue = $actualStringValue;

        return $this;
    }

    public function getActualIntValue(): ?int
    {
        return $this->actualIntValue;
    }

    public function setActualIntValue(?int $actualIntValue): self
    {
        $this->actualIntValue = $actualIntValue;

        return $this;
    }

    public function isMedalRule(): bool
    {
        return $this->isMedalRule;
    }

    public function setIsMedalRule(bool $isMedalRule): self
    {
        $this->isMedalRule = $isMedalRule;

        return $this;
    }

    public function hasActualValue(): bool
    {
        return match ($this->valueType) {
            BetValueType::Team => $this->actualTeamValue !== null,
            BetValueType::String => $this->actualStringValue !== null,
            BetValueType::Integer => $this->actualIntValue !== null,
        };
    }
}
