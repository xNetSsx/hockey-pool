<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Service\Manager\GameManager;
use App\Service\Resolver\TournamentResolver;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Fetches match results from TheSportsDB and updates unfinished games.
 */
final readonly class ResultFetcherService
{
    public function __construct(
        private SportsDbClient $sportsDbClient,
        private GameRepository $gameRepository,
        private GameManager $gameManager,
        private TournamentResolver $tournamentResolver,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @return array{updated: int, checked: int, errors: list<string>}
     */
    public function fetchAndUpdate(Tournament $tournament): array
    {
        $unfinishedGames = $this->gameRepository->findUnfinishedByTournament($tournament);

        if ([] === $unfinishedGames) {
            return ['updated' => 0, 'checked' => 0, 'errors' => []];
        }

        // Group games by date to minimize API calls
        /** @var array<string, list<Game>> $gamesByDate */
        $gamesByDate = [];
        foreach ($unfinishedGames as $game) {
            $date = $game->getPlayedAt()->format('Y-m-d');
            $gamesByDate[$date][] = $game;
        }

        $updated = 0;
        $errors = [];
        $updatedGames = [];

        foreach ($gamesByDate as $date => $games) {
            try {
                $events = $this->sportsDbClient->fetchIihfEventsByDay($date);
            } catch (Throwable $e) {
                $errors[] = sprintf('API error for %s: %s', $date, $e->getMessage());
                $this->logger->warning('SportsDB API error for date {date}: {error}', [
                    'date' => $date,
                    'error' => $e->getMessage(),
                ]);

                continue;
            }

            foreach ($games as $game) {
                $match = $this->matchEventToGame($game, $events);

                if (null === $match) {
                    continue;
                }

                $homeScore = is_numeric($match['intHomeScore'] ?? null) ? (int) $match['intHomeScore'] : 0;
                $awayScore = is_numeric($match['intAwayScore'] ?? null) ? (int) $match['intAwayScore'] : 0;

                $game->setHomeScore($homeScore);
                $game->setAwayScore($awayScore);
                $game->setIsFinished(true);

                // Detect tiebreaker: game went to OT/shootout (regulation was tied)
                $rawStatus = $match['strStatus'] ?? '';
                $rawResult = $match['strResult'] ?? '';
                $status = is_string($rawStatus) ? $rawStatus : '';
                $result = is_string($rawResult) ? $rawResult : '';
                $wentToOvertime = in_array($status, ['AET', 'AP'], true)
                    || str_contains(strtolower($result), 'overtime')
                    || str_contains(strtolower($result), 'penalt')
                    || str_contains(strtolower($result), 'shootout')
                    || $homeScore === $awayScore;
                $game->setIsTiebreaker($wentToOvertime);

                $updatedGames[] = $game;
                $updated++;

                $this->logger->info('Updated game {home} vs {away}: {homeScore}:{awayScore}', [
                    'home' => $game->getHomeTeam()->getCode(),
                    'away' => $game->getAwayTeam()->getCode(),
                    'homeScore' => $homeScore,
                    'awayScore' => $awayScore,
                ]);
            }
        }

        if ([] !== $updatedGames) {
            $this->gameManager->saveAll($updatedGames);
            $this->tournamentResolver->recalculateAll($tournament);
        }

        return [
            'updated' => $updated,
            'checked' => count($unfinishedGames),
            'errors' => $errors,
        ];
    }

    /**
     * @param Game $game
     * @param list<array<string, mixed>> $events
     * @return array<string, mixed>|null
     */
    private function matchEventToGame(Game $game, array $events): ?array
    {
        $homeCode = $game->getHomeTeam()->getCode();
        $awayCode = $game->getAwayTeam()->getCode();

        foreach ($events as $event) {
            $status = $event['strStatus'] ?? '';

            if (!in_array($status, ['Match Finished', 'FT', 'AET', 'AP'], true)) {
                continue;
            }

            $rawHome = $event['strHomeTeam'] ?? '';
            $rawAway = $event['strAwayTeam'] ?? '';
            $eventHome = $this->sportsDbClient->resolveTeamCode(is_string($rawHome) ? $rawHome : '');
            $eventAway = $this->sportsDbClient->resolveTeamCode(is_string($rawAway) ? $rawAway : '');

            if ($eventHome === $homeCode && $eventAway === $awayCode) {
                return $event;
            }

            // Also check reversed (some APIs swap home/away for neutral venues)
            if ($eventHome === $awayCode && $eventAway === $homeCode) {
                // Swap scores
                return array_merge($event, [
                    'intHomeScore' => $event['intAwayScore'] ?? 0,
                    'intAwayScore' => $event['intHomeScore'] ?? 0,
                ]);
            }
        }

        return null;
    }
}
