<?php

declare(strict_types=1);

namespace App\Controller\Prediction;

use App\Entity\SpecialBet;
use App\Entity\TournamentParticipant;
use App\Entity\User;
use App\Enum\TournamentStatus;
use App\Repository\PointEntryRepository;
use App\Repository\SpecialBetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TournamentParticipantRepository;
use App\Service\Provider\ActiveTournamentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SpecialBetOverviewController extends AbstractController
{
    #[Route('/special-bets', name: 'special_bet_overview')]
    public function overview(
        ActiveTournamentProvider $activeTournamentProvider,
        SpecialBetRuleRepository $ruleRepository,
        SpecialBetRepository $betRepository,
        TournamentParticipantRepository $participantRepository,
        PointEntryRepository $pointEntryRepository,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->render('prediction/special_overview.html.twig', [
                'tournament' => null,
                'rules' => [],
                'players' => [],
                'betsByRuleAndUser' => [],
                'pointsByRuleAndUser' => [],
                'currentUserId' => null,
            ]);
        }

        if ($tournament->getStatus() === TournamentStatus::Upcoming) {
            $this->addFlash('error', 'Tipy ostatních se zobrazí po začátku turnaje.');

            return $this->redirectToRoute('prediction_special');
        }

        $rules = $ruleRepository->findByTournament($tournament);
        $participants = $participantRepository->findByTournament($tournament);
        $players = array_map(
            static fn (TournamentParticipant $p) => $p->getUser(),
            $participants,
        );

        /** @var array<int, array<int, SpecialBet>> $betsByRuleAndUser */
        $betsByRuleAndUser = [];

        $allBets = $betRepository->findByTournament($tournament);
        foreach ($allBets as $bet) {
            $ruleId = (int) $bet->getRule()->getId();
            $userId = (int) $bet->getUser()->getId();
            $betsByRuleAndUser[$ruleId][$userId] = $bet;
        }

        /** @var array<int, array<int, float>> $pointsByRuleAndUser */
        $pointsByRuleAndUser = [];

        $pointEntries = $pointEntryRepository->findSpecialBetEntries($tournament);
        foreach ($pointEntries as $entry) {
            $rule = $entry->getSpecialBetRule();
            if (null === $rule) {
                continue;
            }

            $ruleId = (int) $rule->getId();
            $userId = (int) $entry->getUser()->getId();
            $pointsByRuleAndUser[$ruleId][$userId] = $entry->getPoints();
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        return $this->render('prediction/special_overview.html.twig', [
            'tournament' => $tournament,
            'rules' => $rules,
            'players' => $players,
            'betsByRuleAndUser' => $betsByRuleAndUser,
            'pointsByRuleAndUser' => $pointsByRuleAndUser,
            'currentUserId' => $currentUser->getId(),
        ]);
    }
}
