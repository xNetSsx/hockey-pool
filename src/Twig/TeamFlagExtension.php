<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TeamFlagExtension extends AbstractExtension
{
    private const array CODE_MAP = [
        'CZE' => 'cz', 'SVK' => 'sk', 'FIN' => 'fi', 'SWE' => 'se',
        'ITA' => 'it', 'SUI' => 'ch', 'FRA' => 'fr', 'CAN' => 'ca',
        'LAT' => 'lv', 'USA' => 'us', 'GER' => 'de', 'DEN' => 'dk',
        'NOR' => 'no', 'KAZ' => 'kz', 'AUS' => 'au', 'GBR' => 'gb',
        'HUN' => 'hu', 'SLO' => 'si', 'POL' => 'pl',
        'RUS' => 'ru', 'BLR' => 'by', 'BEL' => 'be', 'ROK' => 'kr', 'CHN' => 'cn',
    ];

    public function __construct(
        private readonly Packages $packages,
    ) {
    }

    /** @return list<TwigFunction> */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('team_flag', $this->teamFlag(...), ['is_safe' => ['html']]),
        ];
    }

    public function teamFlag(string $code, string $class = 'inline-block h-4 w-6 rounded-sm'): string
    {
        $iso = self::CODE_MAP[strtoupper($code)] ?? null;

        if (null === $iso) {
            return '';
        }

        $url = $this->packages->getUrl('images/flags/' . $iso . '.svg');

        return sprintf(
            '<img src="%s" alt="%s" class="%s">',
            htmlspecialchars($url, ENT_QUOTES),
            htmlspecialchars($code, ENT_QUOTES),
            htmlspecialchars($class, ENT_QUOTES),
        );
    }
}
