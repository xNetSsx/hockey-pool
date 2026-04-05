<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\Game;
use App\Entity\SpecialBet;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Enum\BetValueType;
use App\Repository\GameRepository;
use App\Repository\PointEntryRepository;
use App\Repository\PredictionRepository;
use App\Repository\SpecialBetRepository;
use App\Repository\SpecialBetRuleRepository;
use App\Repository\TournamentParticipantRepository;
use App\Service\Builder\LeaderboardBuilder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final readonly class TournamentExportService
{
    public function __construct(
        private GameRepository $gameRepository,
        private PredictionRepository $predictionRepository,
        private PointEntryRepository $pointEntryRepository,
        private TournamentParticipantRepository $participantRepository,
        private LeaderboardBuilder $leaderboardBuilder,
        private SpecialBetRuleRepository $specialBetRuleRepository,
        private SpecialBetRepository $specialBetRepository,
    ) {
    }

    public function buildSpreadsheet(Tournament $tournament): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setTitle($tournament->getName())
            ->setSubject('Záloha dat turnaje');

        $games = $this->fetchGamesFlat($tournament);

        $this->addMatchesSheet($spreadsheet, $games);
        $this->addPredictionsSheet($spreadsheet, $tournament, $games);
        $this->addLeaderboardSheet($spreadsheet, $tournament);
        $this->addOverviewSheet($spreadsheet, $tournament, $games);
        $this->addSpecialBetsSheet($spreadsheet, $tournament);

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * @return list<Game>
     */
    private function fetchGamesFlat(Tournament $tournament): array
    {
        $games = [];
        foreach ($this->gameRepository->findByTournamentGroupedByPhase($tournament) as $phaseGames) {
            foreach ($phaseGames as $game) {
                $games[] = $game;
            }
        }

        return $games;
    }

    public function writeToTempFile(Tournament $tournament): string
    {
        $spreadsheet = $this->buildSpreadsheet($tournament);
        $writer = new Xlsx($spreadsheet);

        $tmpFile = tempnam(sys_get_temp_dir(), 'hockey_pool_export_');
        $writer->save($tmpFile);

        return $tmpFile;
    }

    /**
     * @param list<Game> $games
     */
    private function addOverviewSheet(Spreadsheet $spreadsheet, Tournament $tournament, array $games): void
    {
        $sheet = $spreadsheet->createSheet(0);
        $sheet->setTitle('Přehled');

        $participants = $this->participantRepository->findByTournament($tournament);
        $users = array_map(static fn ($p) => $p->getUser(), $participants);

        $predictionsByUser = $this->predictionRepository->findByUsersAndTournamentIndexedByUserId($users, $tournament);
        $leaderboard = $this->leaderboardBuilder->build($tournament);

        $darkStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $blueStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // ── Leaderboard ──────────────────────────────────────────────────────
        $this->writeHeaderRow($sheet, ['Pořadí', 'Hráč', 'Body', 'Přesné skóre', 'Správný vítěz']);
        $row = 2;
        foreach ($leaderboard as $entry) {
            $sheet->setCellValue('A' . $row, $entry['rank']);
            $sheet->setCellValue('B' . $row, $entry['user']->getUsername());
            $sheet->setCellValue('C' . $row, $entry['totalPoints']);
            $sheet->setCellValue('D' . $row, $entry['exactScores']);
            $sheet->setCellValue('E' . $row, $entry['correctWinners']);
            $row++;
        }

        // ── Blank separator ───────────────────────────────────────────────────
        $row++;

        // ── Matches header ────────────────────────────────────────────────────
        // Row 1 of header: Datum | Čas | Zápas | Skóre | Tipy (merged across user cols)
        $firstUserCol = 'E';
        $lastUserCol = $firstUserCol;
        $tmp = $firstUserCol;
        foreach ($users as $i => $user) {
            if ($i > 0) {
                $tmp++;
            }
            $lastUserCol = $tmp;
        }

        foreach (['A' => 'Datum', 'B' => 'Čas', 'C' => 'Zápas', 'D' => 'Skóre'] as $c => $label) {
            $sheet->setCellValue($c . $row, $label);
            $sheet->getStyle($c . $row)->applyFromArray($darkStyle);
        }

        $sheet->setCellValue($firstUserCol . $row, 'Tipy');
        $sheet->getStyle($firstUserCol . $row)->applyFromArray($blueStyle);
        if ($firstUserCol !== $lastUserCol) {
            $sheet->mergeCells($firstUserCol . $row . ':' . $lastUserCol . $row);
        }

        $row++;

        // Row 2 of header: blank | blank | blank | blank | user names
        $col = $firstUserCol;
        $userColMap = [];
        foreach ($users as $user) {
            $userColMap[(int) $user->getId()] = $col;
            $sheet->setCellValue($col . $row, $user->getUsername());
            $sheet->getStyle($col . $row)->applyFromArray($blueStyle);
            $col++;
        }

        $matchDataRow = $row + 1;
        $row++;

        // ── Match rows ────────────────────────────────────────────────────────
        foreach ($games as $game) {
            $gameId = (int) $game->getId();
            $score = $game->isFinished()
                ? $game->getHomeScore() . ':' . $game->getAwayScore()
                : '—';

            $sheet->setCellValue('A' . $row, $game->getPlayedAt()->format('d.m.Y'));
            $sheet->setCellValue('B' . $row, $game->getPlayedAt()->format('H:i'));
            $sheet->setCellValue('C' . $row, $game->getHomeTeam()->getCode() . ':' . $game->getAwayTeam()->getCode());
            $sheet->setCellValue('D' . $row, $score);

            foreach ($users as $user) {
                $userId = (int) $user->getId();
                $prediction = $predictionsByUser[$userId][$gameId] ?? null;
                $tip = $prediction !== null
                    ? $prediction->getHomeScore() . ':' . $prediction->getAwayScore()
                    : '—';
                $sheet->setCellValue($userColMap[$userId] . $row, $tip);
                $sheet->getStyle($userColMap[$userId] . $row)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $row++;
        }

        // ── Column widths ─────────────────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(7);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(8);

        $col = $firstUserCol;
        foreach ($users as $user) {
            $sheet->getColumnDimension($col)->setWidth(12);
            $col++;
        }

        // Freeze rows above match data so leaderboard + header stay visible
        $sheet->freezePane('A' . $matchDataRow);
    }

    /**
     * @param list<Game> $games
     */
    private function addMatchesSheet(Spreadsheet $spreadsheet, array $games): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Zápasy');

        $this->writeHeaderRow($sheet, ['Datum', 'Fáze', 'Domácí', 'Hosté', 'Skóre', 'Ukončen']);

        $row = 2;
        foreach ($games as $game) {
            $score = $game->isFinished()
                ? $game->getHomeScore() . ':' . $game->getAwayScore()
                : '—';

            $this->writeGameCells($sheet, $row, $game);
            $sheet->setCellValue('E' . $row, $score);
            $sheet->setCellValue('F' . $row, $game->isFinished() ? 'Ano' : 'Ne');
            $row++;
        }

        foreach (['A' => 14, 'B' => 22, 'C' => 8, 'D' => 8, 'E' => 8, 'F' => 10] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }

    /**
     * @param list<Game> $games
     */
    private function addPredictionsSheet(Spreadsheet $spreadsheet, Tournament $tournament, array $games): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Tipy');

        $this->writeHeaderRow($sheet, ['Datum', 'Fáze', 'Domácí', 'Hosté', 'Skutečné skóre', 'Hráč', 'Tip', 'Body']);

        $participants = $this->participantRepository->findByTournament($tournament);
        $users = array_map(static fn ($p) => $p->getUser(), $participants);

        $predictionsByUser = $this->predictionRepository->findByUsersAndTournamentIndexedByUserId($users, $tournament);
        $pointsByUser = $this->pointEntryRepository->getPointsPerMatchByUsers($users, $tournament);

        $row = 2;
        foreach ($games as $game) {
            $gameId = (int) $game->getId();
            $actualScore = $game->isFinished()
                ? $game->getHomeScore() . ':' . $game->getAwayScore()
                : '—';

            foreach ($users as $user) {
                $userId = (int) $user->getId();
                $prediction = $predictionsByUser[$userId][$gameId] ?? null;
                $tip = $prediction !== null
                    ? $prediction->getHomeScore() . ':' . $prediction->getAwayScore()
                    : '—';
                $points = $pointsByUser[$userId][$gameId] ?? 0.0;

                $this->writeGameCells($sheet, $row, $game);
                $sheet->setCellValue('E' . $row, $actualScore);
                $sheet->setCellValue('F' . $row, $user->getUsername());
                $sheet->setCellValue('G' . $row, $tip);
                $sheet->setCellValue('H' . $row, $game->isFinished() ? $points : '—');
                $row++;
            }
        }

        foreach (['A' => 14, 'B' => 22, 'C' => 8, 'D' => 8, 'E' => 14, 'F' => 18, 'G' => 8, 'H' => 8] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }

    private function addLeaderboardSheet(Spreadsheet $spreadsheet, Tournament $tournament): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Žebříček');

        $headers = ['Pořadí', 'Hráč', 'Body', 'Přesné skóre', 'Správný vítěz'];
        $this->writeHeaderRow($sheet, $headers);

        $entries = $this->leaderboardBuilder->build($tournament);

        $row = 2;
        foreach ($entries as $entry) {
            $sheet->setCellValue('A' . $row, $entry['rank']);
            $sheet->setCellValue('B' . $row, $entry['user']->getUsername());
            $sheet->setCellValue('C' . $row, $entry['totalPoints']);
            $sheet->setCellValue('D' . $row, $entry['exactScores']);
            $sheet->setCellValue('E' . $row, $entry['correctWinners']);
            $row++;
        }

        foreach (['A' => 8, 'B' => 18, 'C' => 8, 'D' => 14, 'E' => 16] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }

    private function addSpecialBetsSheet(Spreadsheet $spreadsheet, Tournament $tournament): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Speciální tipy');

        $rules = $this->specialBetRuleRepository->findByTournament($tournament);
        $betsByRule = $this->specialBetRepository->findByTournamentIndexedByRule($tournament);

        /** @var array<int, array<int, SpecialBet>> $betsByRuleAndUser */
        $betsByRuleAndUser = [];
        foreach ($betsByRule as $ruleId => $bets) {
            foreach ($bets as $bet) {
                $betsByRuleAndUser[$ruleId][(int) $bet->getUser()->getId()] = $bet;
            }
        }

        $participants = $this->participantRepository->findByTournament($tournament);
        $users = array_map(static fn ($p) => $p->getUser(), $participants);

        $darkStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // ── Header row ────────────────────────────────────────────────────────
        $sheet->setCellValue('A1', 'Otázka');
        $sheet->getStyle('A1')->applyFromArray($darkStyle);
        $sheet->setCellValue('B1', 'Body');
        $sheet->getStyle('B1')->applyFromArray($darkStyle);
        $sheet->setCellValue('C1', 'Výsledek');
        $sheet->getStyle('C1')->applyFromArray($darkStyle);

        $col = 'D';
        foreach ($users as $user) {
            $sheet->setCellValue($col . '1', $user->getUsername());
            $sheet->getStyle($col . '1')->applyFromArray($darkStyle);
            $col++;
        }

        // ── Data rows ─────────────────────────────────────────────────────────
        $row = 2;
        foreach ($rules as $rule) {
            $ruleId = (int) $rule->getId();
            $actualValue = $rule->hasActualValue()
                ? $this->formatBetValue(
                    $rule->getValueType(),
                    $rule->getActualTeamValue(),
                    $rule->getActualStringValue(),
                    $rule->getActualIntValue(),
                )
                : '—';

            $sheet->setCellValue('A' . $row, $rule->getName());
            $sheet->setCellValue('B' . $row, $rule->getPoints());
            $sheet->setCellValue('C' . $row, $actualValue);

            $col = 'D';
            foreach ($users as $user) {
                $userId = (int) $user->getId();
                $bet = $betsByRuleAndUser[$ruleId][$userId] ?? null;
                $value = $bet !== null
                    ? $this->formatBetValue(
                        $rule->getValueType(),
                        $bet->getTeamValue(),
                        $bet->getStringValue(),
                        $bet->getIntValue(),
                    )
                    : '—';
                $sheet->setCellValue($col . $row, $value);
                $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $col++;
            }

            $row++;
        }

        // ── Column widths ─────────────────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(28);
        $sheet->getColumnDimension('B')->setWidth(8);
        $sheet->getColumnDimension('C')->setWidth(16);

        $col = 'D';
        foreach ($users as $user) {
            $sheet->getColumnDimension($col)->setWidth(14);
            $col++;
        }
    }

    private function formatBetValue(BetValueType $type, ?Team $team, ?string $str, ?int $int): string
    {
        return match ($type) {
            BetValueType::Team => $team !== null ? $team->getCode() : '—',
            BetValueType::String => $str ?? '—',
            BetValueType::Integer => $int !== null ? (string) $int : '—',
        };
    }

    private function writeGameCells(Worksheet $sheet, int $row, Game $game): void
    {
        $sheet->setCellValue('A' . $row, $game->getPlayedAt()->format('d.m.Y H:i'));
        $sheet->setCellValue('B' . $row, $game->getPhase()->value);
        $sheet->setCellValue('C' . $row, $game->getHomeTeam()->getCode());
        $sheet->setCellValue('D' . $row, $game->getAwayTeam()->getCode());
    }

    /**
     * @param list<string> $headers
     */
    private function writeHeaderRow(Worksheet $sheet, array $headers): void
    {
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . 1, $header);
            $sheet->getStyle($col . 1)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $col++;
        }
    }
}
