<?php

declare(strict_types=1);

namespace App\Controller\Tournament;

use App\Repository\TournamentRepository;
use App\Service\Provider\ActiveTournamentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TournamentController extends AbstractController
{
    #[Route('/tournaments', name: 'tournament_archive')]
    public function archive(TournamentRepository $repo): Response
    {
        return $this->render('tournament/archive.html.twig', [
            'tournaments' => $repo->findBy([], ['year' => 'DESC']),
        ]);
    }

    /**
     * Select a tournament and redirect to the homepage dashboard.
     * /tournaments/{slug}/select and /tournaments/{slug} both do the same thing.
     */
    #[Route('/tournaments/{slug}/select', name: 'tournament_select')]
    #[Route('/tournaments/{slug}', name: 'tournament_dashboard')]
    public function select(
        string $slug,
        TournamentRepository $tournamentRepository,
        ActiveTournamentProvider $activeTournamentProvider,
    ): Response {
        $tournament = $tournamentRepository->findOneBy(['slug' => $slug]);

        if (null === $tournament) {
            throw $this->createNotFoundException('Turnaj nenalezen.');
        }

        $activeTournamentProvider->selectTournament($tournament);

        return $this->redirectToRoute('homepage');
    }
}
