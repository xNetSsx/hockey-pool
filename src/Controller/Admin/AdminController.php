<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Entity\RuleSet;
use App\Entity\SpecialBetRule;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Entity\TournamentParticipant;
use App\Entity\User;
use App\Enum\TournamentPhase;
use App\Enum\TournamentStatus;
use App\Form\Admin\BulkGameType;
use App\Form\Admin\GameType;
use App\Form\Admin\RuleSetType;
use App\Form\Admin\SpecialBetRuleType;
use App\Form\Admin\TeamType;
use App\Form\Admin\TournamentType;
use App\Form\Admin\UserType;
use App\Form\MatchResultType;
use App\Repository\GameRepository;
use App\Repository\RuleSetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TeamRepository;
use App\Repository\TournamentParticipantRepository;
use App\Repository\TournamentRepository;
use App\Repository\UserRepository;
use App\Service\Manager\GameManager;
use App\Service\Manager\RuleSetManager;
use App\Service\Manager\SpecialBetRuleManager;
use App\Service\Manager\TeamManager;
use App\Service\Manager\TournamentManager;
use App\Service\Manager\UserManager;
use App\Service\Provider\ActiveTournamentProvider;
use App\Service\Resolver\TournamentResolver;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/tournaments', name: 'admin_tournaments')]
    public function tournaments(
        TournamentRepository $repo,
        RuleSetRepository $ruleSetRepo,
        SpecialBetRuleRepository $specialBetRuleRepo,
    ): Response {
        $all = $repo->findBy([], ['year' => 'DESC']);

        $active = [];
        $archived = [];

        foreach ($all as $tournament) {
            if ($tournament->getStatus() === TournamentStatus::Finished) {
                $archived[] = $tournament;
            } else {
                $active[] = $tournament;
            }
        }

        $hasRuleSet = [];
        $hasSpecialRules = [];

        foreach ($all as $tournament) {
            $id = $tournament->getId();
            $hasRuleSet[$id] = null !== $ruleSetRepo->findByTournament($tournament);
            $hasSpecialRules[$id] = count($specialBetRuleRepo->findByTournament($tournament)) > 0;
        }

        return $this->render('admin/tournaments.html.twig', [
            'active' => $active,
            'archived' => $archived,
            'hasRuleSet' => $hasRuleSet,
            'hasSpecialRules' => $hasSpecialRules,
        ]);
    }

    #[Route('/tournaments/new', name: 'admin_tournament_new')]
    public function tournamentNew(Request $request, TournamentManager $manager): Response
    {
        $tournament = new Tournament();
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($tournament);
            $this->addFlash('success', 'Turnaj vytvořen.');

            return $this->redirectToRoute('admin_tournaments');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Nový turnaj',
            'back' => 'admin_tournaments',
        ]);
    }

    #[Route('/tournaments/{id}/edit', name: 'admin_tournament_edit', requirements: ['id' => '\d+'])]
    public function tournamentEdit(Tournament $tournament, Request $request, TournamentManager $manager): Response
    {
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($tournament);
            $this->addFlash('success', 'Turnaj upraven.');

            return $this->redirectToRoute('admin_tournaments');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Upravit turnaj: ' . $tournament->getName(),
            'back' => 'admin_tournaments',
        ]);
    }

    #[Route('/tournaments/{id}/ruleset', name: 'admin_tournament_ruleset', requirements: ['id' => '\d+'])]
    public function tournamentRuleSet(
        Tournament $tournament,
        Request $request,
        RuleSetRepository $ruleSetRepo,
        RuleSetManager $ruleSetManager,
    ): Response {
        $ruleSet = $ruleSetRepo->findByTournament($tournament);

        if (null === $ruleSet) {
            $ruleSet = new RuleSet();
            $ruleSet->setTournament($tournament);
        }

        $form = $this->createForm(RuleSetType::class, $ruleSet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ruleSetManager->save($ruleSet);
            $this->addFlash('success', 'Pravidla bodování uložena.');

            return $this->redirectToRoute('admin_tournaments');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Bodování: ' . $tournament->getName(),
            'back' => 'admin_tournaments',
        ]);
    }

    #[Route('/tournaments/{id}/clone-from-last', name: 'admin_tournament_clone', requirements: ['id' => '\d+'])]
    public function tournamentClone(
        Tournament $tournament,
        TournamentRepository $tournamentRepo,
        TournamentManager $tournamentManager,
    ): Response {
        $source = $tournamentRepo->findLatestWithRules($tournament);

        if (null === $source) {
            $this->addFlash('error', 'Žádný turnaj s pravidly k zkopírování.');

            return $this->redirectToRoute('admin_tournaments');
        }

        $result = $tournamentManager->cloneRulesFrom($source, $tournament);

        if ($result['ruleSetCloned'] || $result['rulesCloned'] > 0) {
            $this->addFlash('success', sprintf(
                'Zkopírováno z "%s": %s%s',
                $source->getName(),
                $result['ruleSetCloned'] ? 'bodování' : '',
                $result['rulesCloned'] > 0
                    ? ($result['ruleSetCloned'] ? ' + ' : '') . $result['rulesCloned'] . ' pravidel'
                    : '',
            ));
        } else {
            $this->addFlash('info', 'Turnaj už má vlastní pravidla — nic nebylo zkopírováno.');
        }

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

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $repo): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $repo->findBy([], ['username' => 'ASC']),
        ]);
    }

    #[Route('/users/new', name: 'admin_user_new')]
    public function userNew(Request $request, UserManager $manager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var bool $admin */
            $admin = $form->get('admin')->getData();
            $user->setRoles($admin ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER']);
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $manager->hashPassword($user, $plainPassword);
            $manager->save($user);

            $this->addFlash('success', 'Uživatel vytvořen.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Nový uživatel',
            'back' => 'admin_users',
        ]);
    }

    #[Route('/users/{id}/edit', name: 'admin_user_edit', requirements: ['id' => '\d+'])]
    public function userEdit(User $user, Request $request, UserManager $manager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->get('admin')->setData($user->hasRole('ROLE_ADMIN'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var bool $admin */
            $admin = $form->get('admin')->getData();
            $user->setRoles($admin ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER']);

            /** @var string|null $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $manager->hashPassword($user, $plainPassword);
            }

            $manager->save($user);
            $this->addFlash('success', 'Uživatel upraven.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Upravit: ' . $user->getUsername(),
            'back' => 'admin_users',
        ]);
    }

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
            'title' => sprintf('Upravit zápas: %s vs %s', $game->getHomeTeam()->getCode(), $game->getAwayTeam()->getCode()),
            'back' => 'admin_matches',
        ]);
    }

    #[Route('/matches/bulk', name: 'admin_match_bulk')]
    public function matchBulk(Request $request, GameManager $manager): Response
    {
        $form = $this->createForm(BulkGameType::class, ['games' => [[], [], [], []]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{tournament: Tournament, phase: TournamentPhase, games: list<array{homeTeam: Team|null, awayTeam: Team|null, playedAt: DateTime|null}>} $data */
            $data = $form->getData();
            $games = [];

            foreach ($data['games'] as $row) {
                if ($row['homeTeam'] === null || $row['awayTeam'] === null || $row['playedAt'] === null) {
                    continue;
                }

                $games[] = Game::create($data['tournament'], $data['phase'], $row['homeTeam'], $row['awayTeam'], $row['playedAt']);
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

    #[Route('/rules', name: 'admin_rules')]
    public function rules(
        ActiveTournamentProvider $activeTournamentProvider,
        SpecialBetRuleRepository $ruleRepo,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();
        $rules = $tournament ? $ruleRepo->findByTournament($tournament) : [];

        return $this->render('admin/rules.html.twig', [
            'tournament' => $tournament,
            'rules' => $rules,
        ]);
    }

    #[Route('/rules/new', name: 'admin_rule_new')]
    public function ruleNew(Request $request, SpecialBetRuleManager $manager): Response
    {
        $rule = new SpecialBetRule();
        $form = $this->createForm(SpecialBetRuleType::class, $rule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($rule);
            $this->addFlash('success', sprintf('Pravidlo "%s" vytvořeno.', $rule->getName()));

            return $this->redirectToRoute('admin_rules');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Nové pravidlo speciálního tipu',
            'back' => 'admin_rules',
        ]);
    }

    #[Route('/rules/{id}/edit', name: 'admin_rule_edit', requirements: ['id' => '\d+'])]
    public function ruleEdit(SpecialBetRule $rule, Request $request, SpecialBetRuleManager $manager): Response
    {
        $form = $this->createForm(SpecialBetRuleType::class, $rule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($rule);
            $this->addFlash('success', sprintf('Pravidlo "%s" upraveno.', $rule->getName()));

            return $this->redirectToRoute('admin_rules');
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form,
            'title' => 'Upravit pravidlo: ' . $rule->getName(),
            'back' => 'admin_rules',
        ]);
    }

    #[Route('/special-results/{id?}', name: 'admin_special_results', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function specialResults(
        ActiveTournamentProvider $activeTournamentProvider,
        TournamentRepository $tournamentRepo,
        SpecialBetRuleRepository $ruleRepo,
        TeamRepository $teamRepository,
        ?int $id = null,
    ): Response {
        if (null !== $id) {
            $tournament = $tournamentRepo->find($id);
        } else {
            $tournament = $activeTournamentProvider->getActiveTournament();
        }

        if (null === $tournament) {
            $this->addFlash('error', 'Žádný turnaj.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/special_results.html.twig', [
            'tournament' => $tournament,
            'tournaments' => $tournamentRepo->findBy([], ['year' => 'DESC']),
            'rules' => $ruleRepo->findByTournament($tournament),
            'teams' => $teamRepository->findByTournament($tournament),
        ]);
    }

    #[Route('/special-results/{id}', name: 'admin_special_results_save', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsCsrfTokenValid('special_results')]
    public function specialResultsSave(
        Tournament $tournament,
        Request $request,
        SpecialBetRuleRepository $ruleRepo,
        SpecialBetRuleManager $ruleManager,
        TournamentResolver $tournamentResolver,
    ): Response {
        $rules = $ruleRepo->findByTournament($tournament);
        $rawValues = [];
        foreach ($rules as $rule) {
            $ruleId = $rule->getId();
            $rawValues[$ruleId] = $request->getPayload()->getString('rule_' . $ruleId);
        }

        $ruleManager->updateActualValues($rules, $rawValues);
        $tournamentResolver->resolveSpecialBets($tournament);

        $this->addFlash('success', sprintf('Výsledky uloženy a speciální tipy přepočteny pro "%s".', $tournament->getName()));

        return $this->redirectToRoute('admin_special_results', ['id' => $tournament->getId()]);
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

    /** Participants. */
    #[Route('/participants', name: 'admin_participants')]
    public function participants(
        ActiveTournamentProvider $activeTournamentProvider,
        TournamentParticipantRepository $participantRepo,
        UserRepository $userRepo,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            $this->addFlash('error', 'Žádný aktivní turnaj.');

            return $this->redirectToRoute('admin_dashboard');
        }

        $participants = $participantRepo->findByTournament($tournament);
        $participantUserIds = array_map(static fn (TournamentParticipant $p) => $p->getUser()->getId(), $participants);
        $allUsers = $userRepo->findBy([], ['username' => 'ASC']);

        return $this->render('admin/participants.html.twig', [
            'tournament' => $tournament,
            'participants' => $participants,
            'availableUsers' => array_filter($allUsers, static fn (User $u) => !in_array($u->getId(), $participantUserIds, true)),
        ]);
    }

    #[Route('/participants/add', name: 'admin_participant_add', methods: ['POST'])]
    #[IsCsrfTokenValid('participant_add')]
    public function participantAdd(
        Request $request,
        ActiveTournamentProvider $activeTournamentProvider,
        UserRepository $userRepo,
        TournamentParticipantRepository $participantRepo,
        EntityManagerInterface $em,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $userId = $request->getPayload()->getInt('user_id');
        $user = $userRepo->find($userId);

        if (null !== $user && !$participantRepo->isParticipant($user, $tournament)) {
            $participant = new TournamentParticipant();
            $participant->setUser($user);
            $participant->setTournament($tournament);
            $participant->setPaid(true);
            $em->persist($participant);
            $em->flush();

            $this->addFlash('success', sprintf('%s přidán do turnaje.', $user->getUsername()));
        }

        return $this->redirectToRoute('admin_participants');
    }

    #[Route('/participants/{id}/remove', name: 'admin_participant_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsCsrfTokenValid('participant_remove')]
    public function participantRemove(
        TournamentParticipant $participant,
        EntityManagerInterface $em,
    ): Response {
        $username = $participant->getUser()->getUsername();
        $em->remove($participant);
        $em->flush();

        $this->addFlash('success', sprintf('%s odebrán z turnaje.', $username));

        return $this->redirectToRoute('admin_participants');
    }

    #[Route('/participants/{id}/toggle-paid', name: 'admin_participant_toggle_paid', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsCsrfTokenValid('participant_paid')]
    public function participantTogglePaid(
        TournamentParticipant $participant,
        EntityManagerInterface $em,
    ): Response {
        $participant->setPaid(!$participant->isPaid());
        $em->flush();

        return $this->redirectToRoute('admin_participants');
    }

    #[Route('/tournaments/{id}/content/{field}', name: 'admin_tournament_content', requirements: ['id' => '\d+', 'field' => 'rules|manual'], methods: ['GET'])]
    public function tournamentContent(Tournament $tournament, string $field): Response
    {
        $content = $field === 'rules' ? $tournament->getRulesContent() : $tournament->getManualContent();

        return $this->render('admin/tournament_content_edit.html.twig', [
            'tournament' => $tournament,
            'field' => $field,
            'title' => $field === 'rules' ? 'Pravidla' : 'Manuál',
            'content' => $content ?? '',
        ]);
    }

    #[Route('/tournaments/{id}/content/{field}', name: 'admin_tournament_content_save', requirements: ['id' => '\d+', 'field' => 'rules|manual'], methods: ['POST'])]
    #[IsCsrfTokenValid('tournament_content')]
    public function tournamentContentSave(
        Tournament $tournament,
        string $field,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $content = $request->getPayload()->getString('content');

        if ($field === 'rules') {
            $tournament->setRulesContent($content ?: null);
        } else {
            $tournament->setManualContent($content ?: null);
        }

        $em->flush();

        $label = $field === 'rules' ? 'Pravidla' : 'Manuál';
        $this->addFlash('success', sprintf('%s pro "%s" uložena.', $label, $tournament->getName()));

        return $this->redirectToRoute('admin_tournament_content', [
            'id' => $tournament->getId(),
            'field' => $field,
        ]);
    }
}
