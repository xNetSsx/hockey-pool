<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\TeamFactory;
use Zenstruck\Foundry\Story;

final class TeamStory extends Story
{
    public const string POOL = 'teams';

    public const array TEAMS = [
        'CZE' => ['name' => 'Česká republika', 'flag' => '🇨🇿'],
        'SVK' => ['name' => 'Slovensko', 'flag' => '🇸🇰'],
        'FIN' => ['name' => 'Finsko', 'flag' => '🇫🇮'],
        'SWE' => ['name' => 'Švédsko', 'flag' => '🇸🇪'],
        'ITA' => ['name' => 'Itálie', 'flag' => '🇮🇹'],
        'SUI' => ['name' => 'Švýcarsko', 'flag' => '🇨🇭'],
        'FRA' => ['name' => 'Francie', 'flag' => '🇫🇷'],
        'CAN' => ['name' => 'Kanada', 'flag' => '🇨🇦'],
        'LAT' => ['name' => 'Lotyšsko', 'flag' => '🇱🇻'],
        'USA' => ['name' => 'USA', 'flag' => '🇺🇸'],
        'GER' => ['name' => 'Německo', 'flag' => '🇩🇪'],
        'DEN' => ['name' => 'Dánsko', 'flag' => '🇩🇰'],
        'ROC' => ['name' => 'Ruský olympijský výbor', 'flag' => null],
        'GBR' => ['name' => 'Velká Británie', 'flag' => '🇬🇧'],
        'NOR' => ['name' => 'Norsko', 'flag' => '🇳🇴'],
        'AUS' => ['name' => 'Rakousko', 'flag' => '🇦🇹'],
        'KAZ' => ['name' => 'Kazachstán', 'flag' => '🇰🇿'],
        'POL' => ['name' => 'Polsko', 'flag' => '🇵🇱'],
        'SLO' => ['name' => 'Slovinsko', 'flag' => '🇸🇮'],
        'HUN' => ['name' => 'Maďarsko', 'flag' => '🇭🇺'],
    ];

    public function build(): void
    {
        foreach (self::TEAMS as $code => $data) {
            $this->addState($code, TeamFactory::createOne([
                'code' => $code,
                'name' => $data['name'],
                'flagEmoji' => $data['flag'],
            ]), self::POOL);
        }
    }
}
