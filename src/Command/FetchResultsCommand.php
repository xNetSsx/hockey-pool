<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\TournamentStatus;
use App\Repository\TournamentRepository;
use App\Service\ResultFetcherService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch-results',
    description: 'Fetch match results from TheSportsDB and update unfinished games',
)]
class FetchResultsCommand extends Command
{
    public function __construct(
        private readonly ResultFetcherService $resultFetcherService,
        private readonly TournamentRepository $tournamentRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('all', null, InputOption::VALUE_NONE, 'Fetch for all in-progress tournaments')
            ->addOption('active', null, InputOption::VALUE_NONE, 'Fetch for the in-progress tournament (default)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('all')) {
            $tournaments = $this->tournamentRepository->findBy([
                'status' => TournamentStatus::InProgress,
            ]);
        } else {
            // Default: find the in-progress tournament (no session needed)
            $tournament = $this->tournamentRepository->findOneBy([
                'status' => TournamentStatus::InProgress,
            ]);
            $tournaments = null !== $tournament ? [$tournament] : [];
        }

        if ([] === $tournaments) {
            $io->warning('No in-progress tournaments found.');

            return Command::SUCCESS;
        }

        $totalUpdated = 0;

        foreach ($tournaments as $tournament) {
            $io->section($tournament->getName());

            $result = $this->resultFetcherService->fetchAndUpdate($tournament);

            if ([] !== $result['errors']) {
                foreach ($result['errors'] as $error) {
                    $io->warning($error);
                }
            }

            $io->text(sprintf(
                'Checked %d unfinished games, updated %d.',
                $result['checked'],
                $result['updated'],
            ));

            $totalUpdated += $result['updated'];
        }

        if ($totalUpdated > 0) {
            $io->success(sprintf('Updated %d game(s) and recalculated points.', $totalUpdated));
        } else {
            $io->info('No new results found.');
        }

        return Command::SUCCESS;
    }
}
