<?php

declare(strict_types=1);

namespace App\Twig;

use App\Enum\TournamentPhase;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PhaseExtension extends AbstractExtension
{
    /** @return list<TwigFilter> */
    public function getFilters(): array
    {
        return [
            new TwigFilter('phase_label', $this->phaseLabel(...)),
        ];
    }

    public function phaseLabel(string $value): string
    {
        $phase = TournamentPhase::tryFrom($value);

        return $phase?->label() ?? $value;
    }
}
