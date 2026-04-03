<?php

declare(strict_types=1);

namespace App\Controller\Prediction;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\User;
use App\Form\PredictionType;
use App\Repository\GameRepository;
use App\Repository\PredictionRepository;
use App\Repository\SpecialBetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TeamRepository;
use App\Security\Voter\PredictionVoter;
use App\Security\Voter\SpecialBetVoter;
use App\Service\Manager\PredictionManager;
use App\Service\Manager\SpecialBetManager;
use App\Service\Provider\ActiveTournamentProvider;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

class PredictionController extends AbstractController
{
    #[Route('/predictions', name: 'prediction_list')]
    public function list(
        ActiveTournamentProvider $activeTournamentProvider,
        GameRepository $gameRepository,
        PredictionRepository $predictionRepository,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->render('prediction/empty.html.twig');
        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('prediction/list.html.twig', [
            'tournament' => $tournament,
            'groupedGames' => $gameRepository->findByTournamentGroupedByPhase($tournament),
            'userPredictions' => $predictionRepository->findByUserIndexedByGame($user, $tournament),
            'now' => new DateTimeImmutable(),
        ]);
    }

    #[Route('/predictions/match/{id}', name: 'prediction_edit', requirements: ['id' => '\d+'])]
    public function edit(
        Game $game,
        Request $request,
        PredictionRepository $predictionRepository,
        PredictionManager $predictionManager,
    ): Response {
        $this->denyAccessUnlessGranted(PredictionVoter::CREATE, $game);

        /** @var User $user */
        $user = $this->getUser();

        $prediction = $predictionRepository->findOneBy(['user' => $user, 'game' => $game]);
        $isNew = $prediction === null;

        $form = $this->createForm(PredictionType::class, [
            'homeScore' => $prediction?->getHomeScore(),
            'awayScore' => $prediction?->getAwayScore(),
        ], [
            'home_team_label' => $game->getHomeTeam()->getLabel(),
            'away_team_label' => $game->getAwayTeam()->getLabel(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{homeScore: int, awayScore: int} $data */
            $data = $form->getData();

            if ($isNew) {
                $prediction = new Prediction();
                $prediction->setUser($user);
                $prediction->setGame($game);
            }

            $prediction->setHomeScore($data['homeScore']);
            $prediction->setAwayScore($data['awayScore']);
            $prediction->setUpdatedAt(new DateTimeImmutable());

            $predictionManager->save($prediction);

            $this->addFlash('success', sprintf(
                'Tip uložen: %s %d:%d %s',
                $game->getHomeTeam()->getCode(),
                $data['homeScore'],
                $data['awayScore'],
                $game->getAwayTeam()->getCode(),
            ));

            return $this->redirectToRoute('prediction_list');
        }

        return $this->render('prediction/edit.html.twig', [
            'game' => $game,
            'form' => $form,
            'isNew' => $isNew,
        ]);
    }

    #[Route('/predictions/special', name: 'prediction_special', methods: ['GET'])]
    public function special(
        ActiveTournamentProvider $activeTournamentProvider,
        SpecialBetRuleRepository $ruleRepository,
        SpecialBetRepository $specialBetRepository,
        TeamRepository $teamRepository,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->render('prediction/empty.html.twig');
        }

        /** @var User $user */
        $user = $this->getUser();

        $isLocked = !$this->isGranted(SpecialBetVoter::SUBMIT, $tournament);

        return $this->render('prediction/special.html.twig', [
            'tournament' => $tournament,
            'rules' => $ruleRepository->findByTournament($tournament),
            'existingBets' => $specialBetRepository->findByUserIndexedByRule($user, $tournament),
            'isLocked' => $isLocked,
            'teams' => $teamRepository->findByTournament($tournament),
        ]);
    }

    #[Route('/predictions/special', name: 'prediction_special_save', methods: ['POST'])]
    #[IsCsrfTokenValid('special_bets')]
    public function specialSave(
        Request $request,
        ActiveTournamentProvider $activeTournamentProvider,
        SpecialBetRuleRepository $ruleRepository,
        SpecialBetRepository $specialBetRepository,
        SpecialBetManager $specialBetManager,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->redirectToRoute('prediction_special');
        }

        $this->denyAccessUnlessGranted(SpecialBetVoter::SUBMIT, $tournament);

        /** @var User $user */
        $user = $this->getUser();

        $rules = $ruleRepository->findByTournament($tournament);
        $existingBets = $specialBetRepository->findByUserIndexedByRule($user, $tournament);

        $rawValues = [];
        foreach ($rules as $rule) {
            $ruleId = $rule->getId();
            $rawValues[$ruleId] = $request->getPayload()->getString('rule_' . $ruleId);
        }

        $specialBetManager->updateBets($user, $rules, $existingBets, $rawValues);
        $this->addFlash('success', 'Speciální tipy uloženy.');

        return $this->redirectToRoute('prediction_special');
    }
}
