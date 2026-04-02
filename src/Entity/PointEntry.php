<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PointCategory;
use App\Repository\PointEntryRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PointEntryRepository::class)]
#[ORM\Table(name: 'point_entry')]
#[ORM\Index(columns: ['user_id'], name: 'idx_point_entry_user')]
#[ORM\Index(columns: ['tournament_id'], name: 'idx_point_entry_tournament')]
#[ORM\Index(columns: ['game_id'], name: 'idx_point_entry_game')]
class PointEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Tournament::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private Tournament $tournament;

    #[ORM\ManyToOne(targetEntity: Game::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Game $game = null;

    #[ORM\ManyToOne(targetEntity: SpecialBetRule::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?SpecialBetRule $specialBetRule = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull]
    private float $points;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $reason;

    #[ORM\Column(type: 'string', length: 50, nullable: true, enumType: PointCategory::class)]
    private ?PointCategory $category = null;

    #[ORM\Column]
    private DateTimeImmutable $calculatedAt;

    public function __construct()
    {
        $this->calculatedAt = new DateTimeImmutable();
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

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getSpecialBetRule(): ?SpecialBetRule
    {
        return $this->specialBetRule;
    }

    public function setSpecialBetRule(?SpecialBetRule $specialBetRule): self
    {
        $this->specialBetRule = $specialBetRule;

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

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCategory(): ?PointCategory
    {
        return $this->category;
    }

    public function setCategory(?PointCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCalculatedAt(): DateTimeImmutable
    {
        return $this->calculatedAt;
    }
}
