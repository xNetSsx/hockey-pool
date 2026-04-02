<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Builder;

use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\PointEntryRepository;
use App\Repository\TournamentParticipantRepository;
use App\Repository\UserRepository;
use App\Service\Builder\LeaderboardBuilder;
use PHPUnit\Framework\TestCase;

class LeaderboardBuilderTest extends TestCase
{
    public function testBasicRankingHighestPointsFirst(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');
        $user2 = $this->stubUser(2, 'bob');

        $rows = [
            ['userId' => 1, 'totalPoints' => 10.0],
            ['userId' => 2, 'totalPoints' => 5.0],
        ];

        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [],
            [],
            [1 => $user1, 2 => $user2],
        );

        self::assertCount(2, $result);
        self::assertSame($user1, $result[0]['user']);
        self::assertSame(1, $result[0]['rank']);
        self::assertSame($user2, $result[1]['user']);
        self::assertSame(2, $result[1]['rank']);
    }

    public function testTiebreakerExactScoresBreaksTie(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');
        $user2 = $this->stubUser(2, 'bob');

        $rows = [
            ['userId' => 1, 'totalPoints' => 10.0],
            ['userId' => 2, 'totalPoints' => 10.0],
        ];

        $tiebreaks = [
            1 => ['exactScores' => 3, 'correctWinners' => 5],
            2 => ['exactScores' => 1, 'correctWinners' => 5],
        ];

        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [],
            $tiebreaks,
            [1 => $user1, 2 => $user2],
        );

        self::assertCount(2, $result);
        self::assertSame($user1, $result[0]['user']);
        self::assertSame(1, $result[0]['rank']);
        self::assertSame($user2, $result[1]['user']);
        self::assertSame(2, $result[1]['rank']);
    }

    public function testTiebreakerCorrectWinnersBreaksTieWhenExactScoresEqual(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');
        $user2 = $this->stubUser(2, 'bob');

        $rows = [
            ['userId' => 1, 'totalPoints' => 10.0],
            ['userId' => 2, 'totalPoints' => 10.0],
        ];

        $tiebreaks = [
            1 => ['exactScores' => 2, 'correctWinners' => 7],
            2 => ['exactScores' => 2, 'correctWinners' => 3],
        ];

        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [],
            $tiebreaks,
            [1 => $user1, 2 => $user2],
        );

        self::assertCount(2, $result);
        self::assertSame($user1, $result[0]['user']);
        self::assertSame(1, $result[0]['rank']);
        self::assertSame($user2, $result[1]['user']);
        self::assertSame(2, $result[1]['rank']);
    }

    public function testEqualUsersShareSameRank(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');
        $user2 = $this->stubUser(2, 'bob');

        $rows = [
            ['userId' => 1, 'totalPoints' => 10.0],
            ['userId' => 2, 'totalPoints' => 10.0],
        ];

        $tiebreaks = [
            1 => ['exactScores' => 2, 'correctWinners' => 5],
            2 => ['exactScores' => 2, 'correctWinners' => 5],
        ];

        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [],
            $tiebreaks,
            [1 => $user1, 2 => $user2],
        );

        self::assertCount(2, $result);
        self::assertSame(1, $result[0]['rank']);
        self::assertSame(1, $result[1]['rank']);
    }

    public function testRankAfterTieSkips(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');
        $user2 = $this->stubUser(2, 'bob');
        $user3 = $this->stubUser(3, 'charlie');

        $rows = [
            ['userId' => 1, 'totalPoints' => 10.0],
            ['userId' => 2, 'totalPoints' => 10.0],
            ['userId' => 3, 'totalPoints' => 5.0],
        ];

        $tiebreaks = [
            1 => ['exactScores' => 2, 'correctWinners' => 5],
            2 => ['exactScores' => 2, 'correctWinners' => 5],
        ];

        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [],
            $tiebreaks,
            [1 => $user1, 2 => $user2, 3 => $user3],
        );

        self::assertCount(3, $result);
        self::assertSame(1, $result[0]['rank']);
        self::assertSame(1, $result[1]['rank']);
        self::assertSame(3, $result[2]['rank']);
    }

    public function testUsersMappedWithCorrectFields(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');

        $rows = [
            ['userId' => 1, 'totalPoints' => 7.5],
        ];

        $tiebreaks = [
            1 => ['exactScores' => 4, 'correctWinners' => 6],
        ];

        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [],
            $tiebreaks,
            [1 => $user1],
        );

        self::assertCount(1, $result);
        $entry = $result[0];
        self::assertSame($user1, $entry['user']);
        self::assertEqualsWithDelta(7.5, $entry['totalPoints'], 0.001);
        self::assertSame(1, $entry['rank']);
        self::assertSame(4, $entry['exactScores']);
        self::assertSame(6, $entry['correctWinners']);
    }

    public function testNonParticipantUsersAreExcluded(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');
        $user2 = $this->stubUser(2, 'bob');

        $rows = [
            ['userId' => 1, 'totalPoints' => 10.0],
            ['userId' => 2, 'totalPoints' => 8.0],
        ];

        // Only user 1 is a participant
        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [1],
            [],
            [1 => $user1, 2 => $user2],
        );

        self::assertCount(1, $result);
        self::assertSame($user1, $result[0]['user']);
    }

    public function testEmptyTournamentReturnsEmptyList(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $result = $this->buildLeaderboard(
            $tournament,
            [],
            [],
            [],
            [],
        );

        self::assertSame([], $result);
    }

    public function testMissingTiebreakDataDefaultsToZero(): void
    {
        $tournament = $this->createStub(Tournament::class);

        $user1 = $this->stubUser(1, 'alice');

        $rows = [
            ['userId' => 1, 'totalPoints' => 5.0],
        ];

        // No tiebreak entry for user 1
        $result = $this->buildLeaderboard(
            $tournament,
            $rows,
            [],
            [],
            [1 => $user1],
        );

        self::assertCount(1, $result);
        self::assertSame(0, $result[0]['exactScores']);
        self::assertSame(0, $result[0]['correctWinners']);
    }

    /**
     * @param Tournament $tournament
     * @param array<int, array{userId: int, totalPoints: float}> $rows
     * @param array<int> $participantUserIds
     * @param array<int, array{exactScores: int, correctWinners: int}> $tiebreaks
     * @param array<int, User> $usersById
     * @return list<array{user: User, totalPoints: float, rank: int, exactScores: int, correctWinners: int}>
     */
    private function buildLeaderboard(
        Tournament $tournament,
        array $rows,
        array $participantUserIds,
        array $tiebreaks,
        array $usersById,
    ): array {
        $pointEntryRepo = $this->createStub(PointEntryRepository::class);
        $pointEntryRepo->method('getPointsGroupedByUser')->willReturn($rows);
        $pointEntryRepo->method('getTiebreakCountsByUser')->willReturn($tiebreaks);

        $participantRepo = $this->createStub(TournamentParticipantRepository::class);
        $participantRepo->method('getParticipantUserIds')->willReturn($participantUserIds);

        $userRepo = $this->createStub(UserRepository::class);
        $userRepo->method('findByIds')->willReturn($usersById);

        return (new LeaderboardBuilder($userRepo, $pointEntryRepo, $participantRepo))->build($tournament);
    }

    private function stubUser(int $id, string $username): User
    {
        $user = $this->createStub(User::class);
        $user->method('getId')->willReturn($id);
        $user->method('getUsername')->willReturn($username);

        return $user;
    }
}
