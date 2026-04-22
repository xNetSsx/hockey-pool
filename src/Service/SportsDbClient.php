<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Thin wrapper around TheSportsDB free API.
 *
 * @see https://www.thesportsdb.com/free_sports_api
 */
final readonly class SportsDbClient
{
    private const string BASE_URL = 'https://www.thesportsdb.com/api/v1/json/';

    /**
     * IIHF World Championship league ID on TheSportsDB.
     */
    private const string IIHF_LEAGUE_ID = '4976';

    /**
     * Map TheSportsDB team names → IIHF 3-letter codes used in our DB.
     */
    private const array TEAM_MAP = [
        'Czech Republic' => 'CZE',
        'Czechia' => 'CZE',
        'Canada' => 'CAN',
        'Finland' => 'FIN',
        'Sweden' => 'SWE',
        'USA' => 'USA',
        'United States' => 'USA',
        'Switzerland' => 'SUI',
        'Germany' => 'GER',
        'Slovakia' => 'SVK',
        'Latvia' => 'LAT',
        'Denmark' => 'DEN',
        'Norway' => 'NOR',
        'France' => 'FRA',
        'Kazakhstan' => 'KAZ',
        'Austria' => 'AUS',
        'Great Britain' => 'GBR',
        'Hungary' => 'HUN',
        'Slovenia' => 'SLO',
        'Poland' => 'POL',
        'Italy' => 'ITA',
        'Russia' => 'RUS',
        'Belarus' => 'BLR',
        'Belgium' => 'BEL',
        'South Korea' => 'ROK',
        'China' => 'CHN',
    ];

    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire(env: 'SPORTSDB_API_KEY')]
        private string $apiKey,
    ) {
    }

    /**
     * Fetch IIHF ice hockey events for a given date (filters out NHL/KHL/etc.).
     *
     * @return list<array<string, mixed>>
     */
    public function fetchIihfEventsByDay(string $date): array
    {
        $response = $this->httpClient->request('GET', self::BASE_URL . $this->apiKey . '/eventsday.php', [
            'query' => [
                'd' => $date,
                's' => 'Ice Hockey',
            ],
        ]);

        $data = $response->toArray(false);

        if (!isset($data['events']) || !is_array($data['events'])) {
            return [];
        }

        // Filter to IIHF league only
        $filtered = [];
        foreach ($data['events'] as $event) {
            if (!is_array($event)) {
                continue;
            }

            $leagueId = (string) ($event['idLeague'] ?? '');
            if ($leagueId === self::IIHF_LEAGUE_ID) {
                $filtered[] = $event;
            }
        }

        return $filtered;
    }

    /**
     * Resolve a TheSportsDB team name to our IIHF code.
     *
     * The API appends "Ice Hockey" to team names (e.g. "Czech Republic Ice Hockey").
     */
    public function resolveTeamCode(string $apiTeamName): ?string
    {
        $normalized = preg_replace('/ Ice Hockey$/i', '', $apiTeamName) ?? $apiTeamName;

        return self::TEAM_MAP[$normalized] ?? self::TEAM_MAP[$apiTeamName] ?? null;
    }
}
