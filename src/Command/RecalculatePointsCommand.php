<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\TournamentStatus;
use App\Repository\TournamentRepository;
use App\Service\Resolver\TournamentResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:recalculate-points',
    description: 'Recalculate all points for a tournament or all tournaments',
)]
class RecalculatePointsCommand extends Command
{
    public function __construct(
        private readonly TournamentResolver $tournamentResolver,
        private readonly TournamentRepository $tournamentRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('slug', InputArgument::OPTIONAL, 'Tournament slug (e.g. oh-2026)')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Recalculate all tournaments')
            ->addOption('active', null, InputOption::VALUE_NONE, 'Recalculate only the active (in-progress) tournament');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('all')) {
            return $this->recalculateAll($io);
        }

        if ($input->getOption('active')) {
            $tournament = $this->tournamentRepository->findOneBy(['status' => TournamentStatus::InProgress]);

            if (null === $tournament) {
                $io->warning('No active (in-progress) tournament found.');

                return Command::SUCCESS;
            }

            $io->info(sprintf('Recalculating points for active tournament "%s"...', $tournament->getName()));
            $this->tournamentResolver->recalculateAll($tournament);
            $io->success('Done.');

            return Command::SUCCESS;
        }

        /** @var string|null $slug */
        $slug = $input->getArgument('slug');

        if (null === $slug) {
            $io->error('Provide a tournament slug or use --all.');

            return Command::FAILURE;
        }

        $tournament = $this->tournamentRepository->findOneBy(['slug' => $slug]);

        if (null === $tournament) {
            $io->error(sprintf('Tournament "%s" not found.', $slug));

            return Command::FAILURE;
        }

        $io->info(sprintf('Recalculating points for "%s"...', $tournament->getName()));
        $this->tournamentResolver->recalculateAll($tournament);
        $io->success('Done.');

        return Command::SUCCESS;
    }

    private function recalculateAll(SymfonyStyle $io): int
    {
        $tournaments = $this->tournamentRepository->findBy([], ['year' => 'ASC']);

        if (count($tournaments) === 0) {
            $io->warning('No tournaments found.');

            return Command::SUCCESS;
        }

        foreach ($tournaments as $tournament) {
            $io->section($tournament->getName());
            $this->tournamentResolver->recalculateAll($tournament);
        }

        $io->success(sprintf('Recalculated %d tournaments.', count($tournaments)));

        return Command::SUCCESS;
    }
}
