<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\SpecialBetRule;
use App\Entity\Tournament;
use App\Form\Admin\SpecialBetRuleType;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TeamRepository;
use App\Repository\TournamentRepository;
use App\Service\Manager\SpecialBetRuleManager;
use App\Service\Provider\ActiveTournamentProvider;
use App\Service\Resolver\TournamentResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/admin')]
class SpecialBetAdminController extends AbstractController
{
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
            $ruleId = (int) $rule->getId();
            $rawValues[$ruleId] = $request->getPayload()->getString('rule_' . $ruleId);
        }

        $ruleManager->updateActualValues($rules, $rawValues);
        $tournamentResolver->resolveSpecialBets($tournament);

        $this->addFlash('success', sprintf('Výsledky uloženy a speciální tipy přepočteny pro "%s".', $tournament->getName()));

        return $this->redirectToRoute('admin_special_results', ['id' => $tournament->getId()]);
    }
}
