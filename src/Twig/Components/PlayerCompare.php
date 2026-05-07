<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\TournamentParticipantRepository;
use App\Repository\UserRepository;
use App\Service\Builder\LeaderboardBuilder;
use App\Service\Builder\PlayerComparisonBuilder;
use App\Service\Builder\PlayerStatsBuilder;
use App\Service\Provider\ActiveTournamentProvider;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class PlayerCompare
{
    use DefaultActionTrait;

    /**
     * @var list<string>
     */
    #[LiveProp(writable: true)]
    public array $selected = [];

    public function __construct(
        private readonly ActiveTournamentProvider $activeTournamentProvider,
        private readonly TournamentParticipantRepository $participantRepository,
        private readonly UserRepository $userRepository,
        private readonly LeaderboardBuilder $leaderboardBuilder,
        private readonly PlayerStatsBuilder $playerStatsBuilder,
        private readonly PlayerComparisonBuilder $comparisonBuilder,
    ) {
    }

    /** @return list<User> */
    public function getPlayers(): array
    {
        $tournament = $this->activeTournamentProvider->getActiveTournament();

        if (null !== $tournament) {
            $players = [];
            foreach ($this->participantRepository->findByTournament($tournament) as $participant) {
                $players[] = $participant->getUser();
            }
        } else {
            $players = $this->userRepository->findBy([], ['username' => 'ASC']);
        }

        usort($players, static fn (User $a, User $b) => strcmp($a->getUsername(), $b->getUsername()));

        return $players;
    }

    public function hasComparison(): bool
    {
        return count($this->selected) >= 2;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getComparisonData(): ?array
    {
        if (count($this->selected) < 2) {
            return null;
        }

        $tournament = $this->activeTournamentProvider->getActiveTournament();
        if (null === $tournament) {
            return null;
        }

        /** @var list<User> $users */
        $users = [];
        foreach ($this->selected as $username) {
            $user = $this->userRepository->findOneBy(['username' => $username]);
            if (null !== $user) {
                $users[] = $user;
            }
        }

        if (count($users) < 2) {
            return null;
        }

        $leaderboard = $this->leaderboardBuilder->build($tournament);
        $leaderboardByUserId = [];
        foreach ($leaderboard as $row) {
            $leaderboardByUserId[$row['user']->getId()] = $row;
        }

        $userStats = [];
        foreach ($users as $user) {
            $userStats[$user->getId()] = $this->playerStatsBuilder->build($user, $tournament);
        }

        return [
            'users' => $users,
            'leaderboardByUserId' => $leaderboardByUserId,
            'comparison' => $this->comparisonBuilder->build($users, $tournament),
            'userStats' => $userStats,
        ];
    }

    #[LiveAction]
    public function toggle(#[LiveArg] string $username): void
    {
        if (in_array($username, $this->selected, true)) {
            $this->selected = array_values(array_filter(
                $this->selected,
                static fn (string $u) => $u !== $username,
            ));
        } else {
            $this->selected[] = $username;
        }
    }

    #[LiveAction]
    public function selectAll(): void
    {
        $all = array_map(
            static fn (User $u) => $u->getUsername(),
            $this->getPlayers(),
        );

        if (count($this->selected) === count($all)) {
            $this->selected = [];
        } else {
            $this->selected = $all;
        }
    }
}
