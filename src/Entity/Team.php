<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ORM\Table(name: 'team')]
#[UniqueEntity(fields: ['code'])]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name;

    #[ORM\Column(length: 3, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 3)]
    private string $code;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $flagEmoji = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFlagEmoji(): ?string
    {
        return $this->flagEmoji;
    }

    public function setFlagEmoji(?string $flagEmoji): self
    {
        $this->flagEmoji = $flagEmoji;

        return $this;
    }

    /**
     * Short label: "🇨🇿 CZE".
     */
    public function getLabel(): string
    {
        return trim(($this->flagEmoji ?? '') . ' ' . $this->code);
    }

    /**
     * Full label for dropdowns: "🇨🇿 Česká republika (CZE)".
     */
    public function getFullLabel(): string
    {
        return trim(($this->flagEmoji ?? '') . ' ' . $this->name . ' (' . $this->code . ')');
    }
}
