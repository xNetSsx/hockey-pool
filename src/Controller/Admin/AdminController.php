<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Form\Admin\TeamType;
use App\Repository\TeamRepository;
use App\Service\Manager\TeamManager;
use App\Service\Provider\ActiveTournamentProvider;
use App\Service\Resolver\TournamentResolver;
use App\Service\ResultFetcherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->redirectToRoute('admin_tournaments');
    }

    #[Route('/teams', name: 'admin_teams')]
    public function teams(TeamRepository $repo): Response
    {
        return $this->render('admin/teams.html.twig', [
            'teams' => $repo->findBy([], ['code' => 'ASC']),
        ]);
    }

    #[Route('/teams/new', name: 'admin_team_new')]
    public function teamNew(Request $request, TeamManager $manager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($team);
            $this->addFlash('success', 'Tým vytvořen.');

            return $this->redirectToRoute('admin_teams');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Nový tým',
            'back' => 'admin_teams',
        ]);
    }

    #[Route('/teams/{id}/edit', name: 'admin_team_edit', requirements: ['id' => '\d+'])]
    public function teamEdit(Team $team, Request $request, TeamManager $manager): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($team);
            $this->addFlash('success', 'Tým upraven.');

            return $this->redirectToRoute('admin_teams');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Upravit tým: ' . $team->getName(),
            'back' => 'admin_teams',
        ]);
    }

    #[Route('/recalculate', name: 'admin_recalculate', methods: ['POST'])]
    #[IsCsrfTokenValid('recalculate')]
    public function recalculate(
        ActiveTournamentProvider $activeTournamentProvider,
        TournamentResolver $tournamentResolver,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            $this->addFlash('error', 'Žádný aktivní turnaj.');

            return $this->redirectToRoute('admin_dashboard');
        }

        $tournamentResolver->recalculateAll($tournament);
        $this->addFlash('success', sprintf('Všechny body přepočteny pro "%s".', $tournament->getName()));

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/fetch-results', name: 'admin_fetch_results', methods: ['POST'])]
    #[IsCsrfTokenValid('fetch_results')]
    public function fetchResults(
        ActiveTournamentProvider $activeTournamentProvider,
        ResultFetcherService $resultFetcherService,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            $this->addFlash('error', 'Žádný aktivní turnaj.');

            return $this->redirectToRoute('admin_dashboard');
        }

        $result = $resultFetcherService->fetchAndUpdate($tournament);

        if ($result['updated'] > 0) {
            $this->addFlash('success', sprintf(
                'Aktualizováno %d zápasů z %d (body přepočteny) pro "%s".',
                $result['updated'],
                $result['checked'],
                $tournament->getName(),
            ));
        } else {
            $this->addFlash('info', sprintf(
                'Žádné nové výsledky (%d neodehraných zápasů zkontrolováno) pro "%s".',
                $result['checked'],
                $tournament->getName(),
            ));
        }

        foreach ($result['errors'] as $error) {
            $this->addFlash('error', $error);
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}
