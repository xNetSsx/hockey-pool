<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\RuleSet;
use App\Entity\Tournament;
use App\Enum\TournamentStatus;
use App\Form\Admin\RuleSetType;
use App\Form\Admin\TournamentType;
use App\Repository\RuleSetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TournamentRepository;
use App\Service\Manager\RuleSetManager;
use App\Service\Manager\TournamentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class TournamentAdminController extends AbstractController
{
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

        $hasRuleSetMap = $ruleSetRepo->getHasRuleSetMap($all);
        $hasSpecialRulesMap = $specialBetRuleRepo->getHasSpecialBetRulesMap($all);

        $hasRuleSet = [];
        $hasSpecialRules = [];

        foreach ($all as $tournament) {
            $id = (int) $tournament->getId();
            $hasRuleSet[$id] = isset($hasRuleSetMap[$id]);
            $hasSpecialRules[$id] = isset($hasSpecialRulesMap[$id]);
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
}
