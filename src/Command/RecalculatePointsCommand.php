<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\TournamentRepository;
use App\Service\Builder\LeaderboardBuilder;
use App\Service\Resolver\TournamentResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:recalculate-points',
    description: 'Recalculate all points for a tournament',
)]
class RecalculatePointsCommand extends Command
{
    public function __construct(
        private readonly TournamentResolver $tournamentResolver,
        private readonly LeaderboardBuilder $leaderboardService,
        private readonly TournamentRepository $tournamentRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('slug', InputArgument::REQUIRED, 'Tournament slug (e.g. oh-2026)')
            ->addOption('verify', null, null, 'Verify results against expected OH 2026 standings');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $slug */
        $slug = $input->getArgument('slug');
        $tournament = $this->tournamentRepository->findOneBy(['slug' => $slug]);

        if (null === $tournament) {
            $io->error(sprintf('Tournament "%s" not found.', $slug));

            return Command::FAILURE;
        }

        $io->info(sprintf('Recalculating points for "%s"...', $tournament->getName()));

        $this->tournamentResolver->recalculateAll($tournament);

        $leaderboard = $this->leaderboardService->build($tournament);

        $io->table(
            ['Rank', 'User', 'Points'],
            array_map(
                static fn (array $row) => [$row['rank'], $row['user']->getUsername(), number_format($row['totalPoints'], 2)],
                $leaderboard,
            ),
        );

        if ($input->getOption('verify')) {
            return $this->verify($leaderboard, $io);
        }

        $io->success('Done.');

        return Command::SUCCESS;
    }

    /**
     * @param list<array{user: \App\Entity\User, totalPoints: float, rank: int}> $leaderboard
     */
    private function verify(array $leaderboard, SymfonyStyle $io): int
    {
        $expected = [
            'Martin'  => 46.75,
            'Pavel'   => 45.75,
            'Ondra'   => 44.50,
            'Kuba'    => 44.00,
            'Honza S' => 39.75,
            'Mééča'   => 39.50,
            'Fanda'   => 39.50,
            'Táda'    => 38.75,
            'Váca'    => 36.50,
            'Mates'   => 30.25,
        ];

        $actual = [];
        foreach ($leaderboard as $row) {
            $actual[$row['user']->getUsername()] = $row['totalPoints'];
        }

        $errors = [];

        foreach ($expected as $username => $expectedPoints) {
            $actualPoints = $actual[$username] ?? null;

            if (null === $actualPoints) {
                $errors[] = sprintf('%s: MISSING from leaderboard', $username);
            } elseif (abs($actualPoints - $expectedPoints) > 0.001) {
                $errors[] = sprintf('%s: expected %.2f, got %.2f', $username, $expectedPoints, $actualPoints);
            }
        }

        if (count($errors) > 0) {
            $io->error('Verification FAILED:');
            $io->listing($errors);

            return Command::FAILURE;
        }

        $io->success('Verification PASSED — all 10 scores match the spreadsheet.');

        return Command::SUCCESS;
    }
}
