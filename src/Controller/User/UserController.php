<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Repository\GameRepository;
use App\Repository\PredictionRepository;
use App\Repository\SpecialBetRepository;
use App\Repository\UserRepository;
use App\Service\Builder\CareerStatsBuilder;
use App\Service\Builder\LeaderboardBuilder;
use App\Service\Builder\PlayerStatsBuilder;
use App\Service\Provider\ActiveTournamentProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/player/{username}', name: 'player_profile')]
    public function profile(
        string $username,
        UserRepository $userRepository,
        ActiveTournamentProvider $activeTournamentProvider,
        GameRepository $gameRepository,
        PredictionRepository $predictionRepository,
        SpecialBetRepository $specialBetRepository,
        LeaderboardBuilder $leaderboardBuilder,
        PlayerStatsBuilder $playerStatsBuilder,
        CareerStatsBuilder $careerStatsBuilder,
    ): Response {
        $player = $userRepository->findOneBy(['username' => $username]);

        if (null === $player) {
            throw new NotFoundHttpException(sprintf('Hráč "%s" neexistuje.', $username));
        }

        $tournament = $activeTournamentProvider->getActiveTournament();

        $careerStats = $careerStatsBuilder->buildForUser($player);

        if (null === $tournament) {
            return $this->render('user/empty.html.twig', [
                'player' => $player,
                'careerStats' => $careerStats,
            ]);
        }

        $leaderboard = $leaderboardBuilder->build($tournament);
        $playerRank = null;
        $playerPoints = 0.0;

        foreach ($leaderboard as $row) {
            if ($row['user']->getId() === $player->getId()) {
                $playerRank = $row['rank'];
                $playerPoints = $row['totalPoints'];
                break;
            }
        }

        return $this->render('user/profile.html.twig', [
            'player' => $player,
            'tournament' => $tournament,
            'playerRank' => $playerRank,
            'playerPoints' => $playerPoints,
            'totalPlayers' => count($leaderboard),
            'groupedGames' => $gameRepository->findByTournamentGroupedByPhase($tournament),
            'userPredictions' => $predictionRepository->findByUserIndexedByGame($player, $tournament),
            'specialBets' => $specialBetRepository->findByUserIndexedByRule($player, $tournament),
            'stats' => $playerStatsBuilder->build($player, $tournament),
            'careerStats' => $careerStats,
        ]);
    }

    #[Route('/compare', name: 'player_compare')]
    public function compare(
        Request $request,
    ): Response {
        /** @var list<string> $usernames */
        $usernames = $request->query->all('users');

        return $this->render('user/compare_select.html.twig', [
            'preselected' => $usernames,
        ]);
    }
}
