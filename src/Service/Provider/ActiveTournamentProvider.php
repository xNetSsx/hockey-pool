<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Tournament;
use App\Enum\TournamentStatus;
use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides the currently selected tournament.
 *
 * Priority:
 *  1. Tournament stored in session (user explicitly picked one)
 *  2. In-progress tournament
 *  3. Latest finished tournament
 */
final readonly class ActiveTournamentProvider
{
    private const string SESSION_KEY = 'selected_tournament_slug';

    public function __construct(
        private TournamentRepository $tournamentRepository,
        private RequestStack $requestStack,
    ) {
    }

    public function getActiveTournament(): ?Tournament
    {
        $session = $this->requestStack->getSession();
        /** @var string|null $slug */
        $slug = $session->get(self::SESSION_KEY);

        if (null !== $slug) {
            $tournament = $this->tournamentRepository->findOneBy(['slug' => $slug]);

            if (null !== $tournament) {
                return $tournament;
            }

            $session->remove(self::SESSION_KEY);
        }

        return $this->getDefaultTournament();
    }

    public function selectTournament(Tournament $tournament): void
    {
        $this->requestStack->getSession()->set(self::SESSION_KEY, $tournament->getSlug());
    }

    public function getDefaultTournament(): ?Tournament
    {
        return $this->tournamentRepository->findOneBy(['status' => TournamentStatus::InProgress])
            ?? $this->tournamentRepository->findOneBy(['status' => TournamentStatus::Finished], ['year' => 'DESC']);
    }
}
