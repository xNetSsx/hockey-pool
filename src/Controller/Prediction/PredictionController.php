<?php

declare(strict_types=1);

namespace App\Controller\Prediction;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\SpecialBet;
use App\Entity\Team;
use App\Entity\User;
use App\Enum\BetValueType;
use App\Form\PredictionType;
use App\Repository\GameRepository;
use App\Repository\PredictionRepository;
use App\Repository\SpecialBetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TeamRepository;
use App\Security\Voter\PredictionVoter;
use App\Service\Manager\PredictionManager;
use App\Service\Manager\SpecialBetManager;
use App\Service\Provider\ActiveTournamentProvider;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
            'now' => new DateTime(),
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
            if ($game->getPlayedAt() <= new DateTime() && !$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', 'Zápas už začal, tip nelze měnit.');

                return $this->redirectToRoute('prediction_list');
            }

            /** @var array{homeScore: int, awayScore: int} $data */
            $data = $form->getData();

            if ($isNew) {
                $prediction = new Prediction();
                $prediction->setUser($user);
                $prediction->setGame($game);
            }

            $prediction->setHomeScore($data['homeScore']);
            $prediction->setAwayScore($data['awayScore']);
            $prediction->setUpdatedAt(new DateTime());

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

    #[Route('/predictions/special', name: 'prediction_special')]
    public function special(
        Request $request,
        ActiveTournamentProvider $activeTournamentProvider,
        GameRepository $gameRepository,
        SpecialBetRuleRepository $ruleRepository,
        SpecialBetRepository $specialBetRepository,
        SpecialBetManager $specialBetManager,
        TeamRepository $teamRepository,
        EntityManagerInterface $em,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->render('prediction/empty.html.twig');
        }

        /** @var User $user */
        $user = $this->getUser();

        $firstMatchDate = $gameRepository->findFirstMatchDate($tournament);
        $isLocked = null !== $firstMatchDate && $firstMatchDate <= new DateTime() && !$this->isGranted('ROLE_ADMIN');

        $rules = $ruleRepository->findByTournament($tournament);
        $existingBets = $specialBetRepository->findByUserIndexedByRule($user, $tournament);

        if ($request->isMethod('POST') && !$isLocked) {
            $submittedToken = $request->getPayload()->getString('_token');

            if ($this->isCsrfTokenValid('special_bets', $submittedToken)) {
                $bets = [];

                foreach ($rules as $rule) {
                    $ruleId = $rule->getId();
                    $rawValue = $request->getPayload()->getString('rule_' . $ruleId);

                    if ('' === $rawValue) {
                        continue;
                    }

                    $bet = $existingBets[$ruleId] ?? null;

                    if (null === $bet) {
                        $bet = new SpecialBet();
                        $bet->setUser($user);
                        $bet->setRule($rule);
                    }

                    // Reset all values
                    $bet->setTeamValue(null);
                    $bet->setStringValue(null);
                    $bet->setIntValue(null);

                    match ($rule->getValueType()) {
                        BetValueType::Team => $bet->setTeamValue($em->getReference(Team::class, (int) $rawValue)),
                        BetValueType::String => $bet->setStringValue($rawValue),
                        BetValueType::Integer => $bet->setIntValue((int) $rawValue),
                    };

                    $bets[] = $bet;
                }

                if (count($bets) > 0) {
                    $specialBetManager->saveAll($bets);
                }

                $this->addFlash('success', 'Speciální tipy uloženy.');

                return $this->redirectToRoute('prediction_special');
            }
        }

        return $this->render('prediction/special.html.twig', [
            'tournament' => $tournament,
            'rules' => $rules,
            'existingBets' => $existingBets,
            'isLocked' => $isLocked,
            'teams' => $teamRepository->findBy([], ['name' => 'ASC']),
        ]);
    }
}
