<?php

declare(strict_types=1);

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Enum\BetScoringType;
use App\Enum\TournamentStatus;
use App\Repository\GameRepository;
use App\Repository\PointEntryRepository;
use App\Repository\RuleSetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TournamentParticipantRepository;
use App\Service\Builder\LeaderboardBuilder;
use App\Service\Builder\PointsTimelineBuilder;
use App\Service\Provider\ActiveTournamentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(
        ActiveTournamentProvider $activeTournamentProvider,
        SpecialBetRuleRepository $ruleRepository,
        GameRepository $gameRepository,
        PointEntryRepository $pointEntryRepository,
        LeaderboardBuilder $leaderboardBuilder,
        PointsTimelineBuilder $timelineBuilder,
        RuleSetRepository $ruleSetRepository,
        TournamentParticipantRepository $participantRepository,
    ): Response {
        $tournament = $activeTournamentProvider->getActiveTournament();

        if (null === $tournament) {
            return $this->redirectToRoute('tournament_archive');
        }

        $isFinished = $tournament->getStatus() === TournamentStatus::Finished;

        $medalRules = [];
        if ($isFinished) {
            foreach ($ruleRepository->findByTournament($tournament) as $rule) {
                if ($rule->getScoringType() === BetScoringType::Podium && null !== $rule->getActualTeamValue()) {
                    $medalRules[] = $rule;
                } elseif ($rule->isMedalRule() && null !== $rule->getActualTeamValue()) {
                    $medalRules[] = $rule;
                }
            }
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $paymentQrString = null;
        $paymentAmount = null;

        $ruleSet = $ruleSetRepository->findByTournament($tournament);
        if (null !== $ruleSet && $ruleSet->hasPaymentSettings()) {
            $participant = $participantRepository->findOneBy(['user' => $currentUser, 'tournament' => $tournament]);
            if (null !== $participant && !$participant->isPaid()) {
                $amount = $ruleSet->getPaymentAmount();
                $message = $ruleSet->getPaymentMessage();
                $paymentAmount = $amount;

                $acc = $this->czechAccountToIban(
                    $ruleSet->getPaymentAccountNumber() ?? '',
                    $ruleSet->getPaymentBankCode() ?? '',
                );
                $parts = ['SPD', '1.0', 'ACC:' . $acc];
                if (null !== $amount) {
                    $parts[] = 'AM:' . number_format($amount, 2, '.', '');
                }
                $parts[] = 'CC:' . $ruleSet->getPaymentCurrency();
                if (null !== $message && '' !== $message) {
                    $parts[] = 'MSG:' . $message;
                }
                $paymentQrString = implode('*', $parts);
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'tournament' => $tournament,
            'medalRules' => $medalRules,
            'leaderboard' => $leaderboardBuilder->build($tournament),
            'isFinished' => $isFinished,
            'upcomingMatches' => $isFinished ? [] : $gameRepository->findUpcoming($tournament),
            'currentUser' => $currentUser,
            'matchesPlayed' => $gameRepository->countFinished($tournament),
            'matchesTotal' => $gameRepository->countTotal($tournament),
            'highestMatchScore' => $pointEntryRepository->findHighestMatchScore($tournament),
            'mostExactPredictions' => $pointEntryRepository->findMostExactPredictions($tournament),
            'timeline' => $timelineBuilder->build($tournament),
            'todayPoints' => $isFinished ? [] : $pointEntryRepository->getTodayPointsByUser($tournament),
            'paymentQrString' => $paymentQrString,
            'paymentAmount' => $paymentAmount,
            'leaderboardUpdatedAt' => $pointEntryRepository->findLastCalculatedAt($tournament),
        ]);
    }

    private function czechAccountToIban(string $accountNumber, string $bankCode): string
    {
        $parts = explode('-', $accountNumber);
        if (count($parts) === 2) {
            $prefix = str_pad($parts[0], 6, '0', STR_PAD_LEFT);
            $number = str_pad($parts[1], 10, '0', STR_PAD_LEFT);
        } else {
            $prefix = '000000';
            $number = str_pad($parts[0], 10, '0', STR_PAD_LEFT);
        }

        $bban = str_pad($bankCode, 4, '0', STR_PAD_LEFT) . $prefix . $number;

        $numericString = '';
        foreach (str_split($bban . 'CZ00') as $char) {
            $numericString .= ctype_alpha($char)
                ? (string)(ord(strtoupper($char)) - ord('A') + 10)
                : $char;
        }

        $remainder = 0;
        foreach (str_split($numericString) as $digit) {
            $remainder = ($remainder * 10 + (int)$digit) % 97;
        }

        return 'CZ' . str_pad((string)(98 - $remainder), 2, '0', STR_PAD_LEFT) . $bban;
    }
}
