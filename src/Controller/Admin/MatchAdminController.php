<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use App\Form\Admin\BulkGameType;
use App\Form\Admin\GameType;
use App\Form\MatchResultType;
use App\Repository\GameRepository;
use App\Repository\TournamentRepository;
use App\Service\Manager\GameManager;
use App\Service\Provider\ActiveTournamentProvider;
use App\Service\Resolver\TournamentResolver;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class MatchAdminController extends AbstractController
{
    #[Route('/matches', name: 'admin_matches')]
    public function matches(
        Request $request,
        TournamentRepository $tournamentRepo,
        GameRepository $gameRepo,
        ActiveTournamentProvider $activeTournamentProvider,
    ): Response {
        $tournaments = $tournamentRepo->findBy([], ['year' => 'DESC']);

        $tournamentId = $request->query->getInt('tournament');
        $tournament = $tournamentId
            ? $tournamentRepo->find($tournamentId)
            : $activeTournamentProvider->getActiveTournament();

        $games = $tournament ? $gameRepo->findByTournamentGroupedByPhase($tournament) : [];

        return $this->render('admin/matches.html.twig', [
            'tournament' => $tournament,
            'tournaments' => $tournaments,
            'groupedGames' => $games,
        ]);
    }

    #[Route('/matches/new', name: 'admin_match_new')]
    public function matchNew(Request $request, GameManager $manager): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($game);
            $this->addFlash('success', sprintf(
                'Zápas vytvořen: %s vs %s',
                $game->getHomeTeam()->getCode(),
                $game->getAwayTeam()->getCode(),
            ));

            return $this->redirectToRoute('admin_matches');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Nový zápas',
            'back' => 'admin_matches',
        ]);
    }

    #[Route('/matches/{id}/edit', name: 'admin_match_edit', requirements: ['id' => '\d+'])]
    public function matchEdit(Game $game, Request $request, GameManager $manager): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($game);
            $this->addFlash('success', sprintf(
                'Zápas upraven: %s vs %s',
                $game->getHomeTeam()->getCode(),
                $game->getAwayTeam()->getCode(),
            ));

            return $this->redirectToRoute('admin_matches');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => sprintf(
                'Upravit zápas: %s vs %s',
                $game->getHomeTeam()->getCode(),
                $game->getAwayTeam()->getCode()
            ),
            'back' => 'admin_matches',
        ]);
    }

    #[Route('/matches/bulk', name: 'admin_match_bulk')]
    public function matchBulk(Request $request, GameManager $manager): Response
    {
        $form = $this->createForm(BulkGameType::class, ['games' => [[], [], [], []]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{tournament: Tournament, phase: TournamentPhase, games: list<array{homeTeam: Team|null, awayTeam: Team|null, playedAt: DateTimeImmutable|null}>} $data */
            $data = $form->getData();
            $games = [];

            foreach ($data['games'] as $row) {
                if ($row['homeTeam'] === null || $row['awayTeam'] === null || $row['playedAt'] === null) {
                    continue;
                }

                $games[] = Game::create(
                    $data['tournament'],
                    $data['phase'],
                    $row['homeTeam'],
                    $row['awayTeam'],
                    $row['playedAt']
                );
            }

            if (count($games) > 0) {
                $manager->saveAll($games);
                $this->addFlash('success', sprintf('%d zápasů vytvořeno.', count($games)));
            } else {
                $this->addFlash('error', 'Žádné platné zápasy k vytvoření.');
            }

            return $this->redirectToRoute('admin_matches');
        }

        return $this->render('admin/match_bulk.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/matches/{id}/result', name: 'admin_match_result', requirements: ['id' => '\d+'])]
    public function matchResult(
        Game $game,
        Request $request,
        GameManager $gameManager,
        TournamentResolver $tournamentResolver,
    ): Response {
        $form = $this->createForm(MatchResultType::class, $game, [
            'home_team_label' => $game->getHomeTeam()->getLabel(),
            'away_team_label' => $game->getAwayTeam()->getLabel(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setIsFinished(true);
            $gameManager->save($game);
            $tournamentResolver->resolveMatch($game);

            $this->addFlash('success', sprintf(
                'Výsledek: %s %d:%d %s — body přepočteny.',
                $game->getHomeTeam()->getCode(),
                $game->getHomeScore(),
                $game->getAwayScore(),
                $game->getAwayTeam()->getCode(),
            ));

            return $this->redirectToRoute('admin_matches');
        }

        return $this->render('admin/match_result.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }
}
