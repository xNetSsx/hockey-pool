<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Enum\TournamentPhase;
use App\Enum\TournamentStatus;
use App\Repository\TeamRepository;
use App\Repository\TournamentRepository;
use DateMalformedStringException;
use DateTimeImmutable;
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
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TournamentRepository $tournamentRepository,
        private readonly TeamRepository $teamRepository,
    ) {
        parent::__construct();
    }

    /**
     * @throws DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (null !== $this->tournamentRepository->findOneBy(['slug' => 'ms-2026'])) {
            $io->warning('MS 2026 already exists. Skipping.');

            return Command::SUCCESS;
        }

        $scheduleFile = __DIR__ . '/ms2026-schedule.json';
        /** @var list<array{date: string, time: string, home: string, away: string, phase: string}> $schedule */
        $schedule = json_decode((string) file_get_contents($scheduleFile), true);

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
        foreach ($schedule as $row) {
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
                new DateTimeImmutable($row['date'] . ' ' . $row['time']),
            );

            $this->em->persist($game);
            $count++;
        }

        $this->em->flush();

        $io->success(sprintf('MS 2026 created with %d group stage matches.', $count));

        return Command::SUCCESS;
    }
}
