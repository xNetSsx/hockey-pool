<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SpecialBetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpecialBetRepository::class)]
#[ORM\Table(name: 'special_bet')]
#[ORM\UniqueConstraint(name: 'uniq_special_bet_user_rule', columns: ['user_id', 'rule_id'])]
#[ORM\Index(columns: ['user_id'], name: 'idx_special_bet_user')]
#[ORM\Index(columns: ['rule_id'], name: 'idx_special_bet_rule')]
#[UniqueEntity(fields: ['user', 'rule'])]
class SpecialBet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private User $user;

    #[ORM\ManyToOne(targetEntity: SpecialBetRule::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private SpecialBetRule $rule;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Team $teamValue = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $stringValue = null;

    #[ORM\Column(nullable: true)]
    private ?int $intValue = null;

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

    public function getRule(): SpecialBetRule
    {
        return $this->rule;
    }

    public function setRule(SpecialBetRule $rule): self
    {
        $this->rule = $rule;

        return $this;
    }

    public function getTeamValue(): ?Team
    {
        return $this->teamValue;
    }

    public function setTeamValue(?Team $teamValue): self
    {
        $this->teamValue = $teamValue;

        return $this;
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    public function setStringValue(?string $stringValue): self
    {
        $this->stringValue = $stringValue;

        return $this;
    }

    public function getIntValue(): ?int
    {
        return $this->intValue;
    }

    public function setIntValue(?int $intValue): self
    {
        $this->intValue = $intValue;

        return $this;
    }
}
