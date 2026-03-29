<?php

declare(strict_types=1);

namespace App\Controller\Prediction;

use App\Entity\SpecialBet;
use App\Entity\TournamentParticipant;
use App\Enum\TournamentStatus;
use App\Repository\GameRepository;
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
        GameRepository $gameRepository,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->render('prediction/special_overview.html.twig', [
                'tournament' => null,
                'rules' => [],
                'players' => [],
                'betsByRuleAndUser' => [],
                'locked' => false,
            ]);
        }

        $isStarted = $tournament->getStatus() !== TournamentStatus::Upcoming;

        $rules = $ruleRepository->findByTournament($tournament);
        $participants = $participantRepository->findByTournament($tournament);
        $players = array_map(
            static fn (TournamentParticipant $p) => $p->getUser(),
            $participants,
        );

        /** @var array<int, array<int, SpecialBet>> $betsByRuleAndUser */
        $betsByRuleAndUser = [];

        if ($isStarted) {
            $allBets = $betRepository->findByTournament($tournament);

            foreach ($allBets as $bet) {
                $ruleId = (int) $bet->getRule()->getId();
                $userId = (int) $bet->getUser()->getId();
                $betsByRuleAndUser[$ruleId][$userId] = $bet;
            }
        }

        return $this->render('prediction/special_overview.html.twig', [
            'tournament' => $tournament,
            'rules' => $rules,
            'players' => $players,
            'betsByRuleAndUser' => $betsByRuleAndUser,
            'locked' => !$isStarted,
        ]);
    }
}
