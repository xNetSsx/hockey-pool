<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use App\Enum\TournamentStatus;
use App\Repository\TeamRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-ms2026',
    description: 'Seed MS 2026 tournament with group stage schedule',
)]
class SeedMs2026Command extends Command
{
    /** @var list<array{date: string, time: string, home: string, away: string, phase: string}> */
    private const array SCHEDULE = [
        // Day 1 — May 15
        ['date' => '2026-05-15', 'time' => '16:15', 'home' => 'FIN', 'away' => 'GER', 'phase' => 'group_stage'],
        ['date' => '2026-05-15', 'time' => '16:15', 'home' => 'SWE', 'away' => 'CAN', 'phase' => 'group_stage'],
        ['date' => '2026-05-15', 'time' => '20:15', 'home' => 'SUI', 'away' => 'USA', 'phase' => 'group_stage'],
        ['date' => '2026-05-15', 'time' => '20:15', 'home' => 'DEN', 'away' => 'CZE', 'phase' => 'group_stage'],
        // Day 2 — May 16
        ['date' => '2026-05-16', 'time' => '12:15', 'home' => 'AUS', 'away' => 'GBR', 'phase' => 'group_stage'],
        ['date' => '2026-05-16', 'time' => '12:15', 'home' => 'SVK', 'away' => 'NOR', 'phase' => 'group_stage'],
        ['date' => '2026-05-16', 'time' => '16:15', 'home' => 'FIN', 'away' => 'HUN', 'phase' => 'group_stage'],
        ['date' => '2026-05-16', 'time' => '16:15', 'home' => 'CAN', 'away' => 'ITA', 'phase' => 'group_stage'],
        ['date' => '2026-05-16', 'time' => '20:15', 'home' => 'SUI', 'away' => 'LAT', 'phase' => 'group_stage'],
        ['date' => '2026-05-16', 'time' => '20:15', 'home' => 'SLO', 'away' => 'CZE', 'phase' => 'group_stage'],
        // Day 3 — May 17
        ['date' => '2026-05-17', 'time' => '12:15', 'home' => 'USA', 'away' => 'GBR', 'phase' => 'group_stage'],
        ['date' => '2026-05-17', 'time' => '12:15', 'home' => 'ITA', 'away' => 'SVK', 'phase' => 'group_stage'],
        ['date' => '2026-05-17', 'time' => '16:15', 'home' => 'AUS', 'away' => 'HUN', 'phase' => 'group_stage'],
        ['date' => '2026-05-17', 'time' => '16:15', 'home' => 'SWE', 'away' => 'DEN', 'phase' => 'group_stage'],
        ['date' => '2026-05-17', 'time' => '20:15', 'home' => 'GER', 'away' => 'LAT', 'phase' => 'group_stage'],
        ['date' => '2026-05-17', 'time' => '20:15', 'home' => 'NOR', 'away' => 'SLO', 'phase' => 'group_stage'],
        // Day 4 — May 18
        ['date' => '2026-05-18', 'time' => '16:15', 'home' => 'FIN', 'away' => 'USA', 'phase' => 'group_stage'],
        ['date' => '2026-05-18', 'time' => '16:15', 'home' => 'CAN', 'away' => 'DEN', 'phase' => 'group_stage'],
        ['date' => '2026-05-18', 'time' => '20:15', 'home' => 'SUI', 'away' => 'GER', 'phase' => 'group_stage'],
        ['date' => '2026-05-18', 'time' => '20:15', 'home' => 'CZE', 'away' => 'SWE', 'phase' => 'group_stage'],
        // Day 5 — May 19
        ['date' => '2026-05-19', 'time' => '16:15', 'home' => 'LAT', 'away' => 'AUS', 'phase' => 'group_stage'],
        ['date' => '2026-05-19', 'time' => '16:15', 'home' => 'ITA', 'away' => 'NOR', 'phase' => 'group_stage'],
        ['date' => '2026-05-19', 'time' => '20:15', 'home' => 'HUN', 'away' => 'GBR', 'phase' => 'group_stage'],
        ['date' => '2026-05-19', 'time' => '20:15', 'home' => 'SLO', 'away' => 'SVK', 'phase' => 'group_stage'],
        // Day 6 — May 20
        ['date' => '2026-05-20', 'time' => '16:15', 'home' => 'SUI', 'away' => 'AUS', 'phase' => 'group_stage'],
        ['date' => '2026-05-20', 'time' => '16:15', 'home' => 'CZE', 'away' => 'ITA', 'phase' => 'group_stage'],
        ['date' => '2026-05-20', 'time' => '20:15', 'home' => 'USA', 'away' => 'GER', 'phase' => 'group_stage'],
        ['date' => '2026-05-20', 'time' => '20:15', 'home' => 'SWE', 'away' => 'SLO', 'phase' => 'group_stage'],
        // Day 7 — May 21
        ['date' => '2026-05-21', 'time' => '16:15', 'home' => 'FIN', 'away' => 'LAT', 'phase' => 'group_stage'],
        ['date' => '2026-05-21', 'time' => '16:15', 'home' => 'NOR', 'away' => 'CAN', 'phase' => 'group_stage'],
        ['date' => '2026-05-21', 'time' => '20:15', 'home' => 'SUI', 'away' => 'GBR', 'phase' => 'group_stage'],
        ['date' => '2026-05-21', 'time' => '20:15', 'home' => 'DEN', 'away' => 'SVK', 'phase' => 'group_stage'],
        // Day 8 — May 22
        ['date' => '2026-05-22', 'time' => '16:15', 'home' => 'HUN', 'away' => 'GER', 'phase' => 'group_stage'],
        ['date' => '2026-05-22', 'time' => '16:15', 'home' => 'CAN', 'away' => 'SLO', 'phase' => 'group_stage'],
        ['date' => '2026-05-22', 'time' => '20:15', 'home' => 'FIN', 'away' => 'GBR', 'phase' => 'group_stage'],
        ['date' => '2026-05-22', 'time' => '20:15', 'home' => 'ITA', 'away' => 'SWE', 'phase' => 'group_stage'],
        // Day 9 — May 23
        ['date' => '2026-05-23', 'time' => '12:15', 'home' => 'USA', 'away' => 'LAT', 'phase' => 'group_stage'],
        ['date' => '2026-05-23', 'time' => '12:15', 'home' => 'DEN', 'away' => 'SLO', 'phase' => 'group_stage'],
        ['date' => '2026-05-23', 'time' => '16:15', 'home' => 'SUI', 'away' => 'HUN', 'phase' => 'group_stage'],
        ['date' => '2026-05-23', 'time' => '16:15', 'home' => 'SVK', 'away' => 'CZE', 'phase' => 'group_stage'],
        ['date' => '2026-05-23', 'time' => '20:15', 'home' => 'GER', 'away' => 'AUS', 'phase' => 'group_stage'],
        ['date' => '2026-05-23', 'time' => '20:15', 'home' => 'SWE', 'away' => 'NOR', 'phase' => 'group_stage'],
        // Day 10 — May 24
        ['date' => '2026-05-24', 'time' => '16:15', 'home' => 'LAT', 'away' => 'GBR', 'phase' => 'group_stage'],
        ['date' => '2026-05-24', 'time' => '16:15', 'home' => 'DEN', 'away' => 'ITA', 'phase' => 'group_stage'],
        ['date' => '2026-05-24', 'time' => '20:15', 'home' => 'FIN', 'away' => 'AUS', 'phase' => 'group_stage'],
        ['date' => '2026-05-24', 'time' => '20:15', 'home' => 'CAN', 'away' => 'SVK', 'phase' => 'group_stage'],
        // Day 11 — May 25
        ['date' => '2026-05-25', 'time' => '16:15', 'home' => 'USA', 'away' => 'HUN', 'phase' => 'group_stage'],
        ['date' => '2026-05-25', 'time' => '16:15', 'home' => 'CZE', 'away' => 'NOR', 'phase' => 'group_stage'],
        ['date' => '2026-05-25', 'time' => '20:15', 'home' => 'GER', 'away' => 'GBR', 'phase' => 'group_stage'],
        ['date' => '2026-05-25', 'time' => '20:15', 'home' => 'SLO', 'away' => 'ITA', 'phase' => 'group_stage'],
        // Day 12 — May 26
        ['date' => '2026-05-26', 'time' => '12:15', 'home' => 'HUN', 'away' => 'LAT', 'phase' => 'group_stage'],
        ['date' => '2026-05-26', 'time' => '12:15', 'home' => 'NOR', 'away' => 'DEN', 'phase' => 'group_stage'],
        ['date' => '2026-05-26', 'time' => '16:15', 'home' => 'USA', 'away' => 'AUS', 'phase' => 'group_stage'],
        ['date' => '2026-05-26', 'time' => '16:15', 'home' => 'SVK', 'away' => 'SWE', 'phase' => 'group_stage'],
        ['date' => '2026-05-26', 'time' => '20:15', 'home' => 'SUI', 'away' => 'FIN', 'phase' => 'group_stage'],
        ['date' => '2026-05-26', 'time' => '20:15', 'home' => 'CZE', 'away' => 'CAN', 'phase' => 'group_stage'],
        // Playoff matches will be added manually via admin after group stage
    ];

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TournamentRepository $tournamentRepository,
        private readonly TeamRepository $teamRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (null !== $this->tournamentRepository->findOneBy(['slug' => 'ms-2026'])) {
            $io->warning('MS 2026 already exists. Skipping.');

            return Command::SUCCESS;
        }

        $tournament = new Tournament();
        $tournament->setName('MS 2026');
        $tournament->setYear(2026);
        $tournament->setSlug('ms-2026');
        $tournament->setStatus(TournamentStatus::Upcoming);
        $this->em->persist($tournament);

        $teams = [];
        foreach ($this->teamRepository->findAll() as $team) {
            $teams[$team->getCode()] = $team;
        }

        $count = 0;
        foreach (self::SCHEDULE as $row) {
            $home = $teams[$row['home']] ?? null;
            $away = $teams[$row['away']] ?? null;

            if (null === $home || null === $away) {
                $io->warning(sprintf('Unknown team: %s or %s', $row['home'], $row['away']));

                continue;
            }

            $game = Game::create(
                $tournament,
                TournamentPhase::from($row['phase']),
                $home,
                $away,
                new \DateTime($row['date'] . ' ' . $row['time']),
            );

            $this->em->persist($game);
            $count++;
        }

        $this->em->flush();

        $io->success(sprintf('MS 2026 created with %d group stage matches.', $count));

        return Command::SUCCESS;
    }
}
