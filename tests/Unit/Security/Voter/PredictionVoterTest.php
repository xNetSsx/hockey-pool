<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\User;
use App\Security\Voter\PredictionVoter;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class PredictionVoterTest extends TestCase
{
    private PredictionVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new PredictionVoter();
    }

    public function testCanCreatePredictionBeforeMatchStarts(): void
    {
        $game = $this->stubGame(new DateTime('+1 hour'));
        $token = $this->tokenForUser($this->stubUser(1));

        self::assertSame(VoterInterface::ACCESS_GRANTED, $this->voter->vote($token, $game, [PredictionVoter::CREATE]));
    }

    public function testCannotCreatePredictionAfterMatchStarts(): void
    {
        $game = $this->stubGame(new DateTime('-1 hour'));
        $token = $this->tokenForUser($this->stubUser(1));

        self::assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($token, $game, [PredictionVoter::CREATE]));
    }

    public function testAdminCanCreatePredictionAfterMatchStarts(): void
    {
        $game = $this->stubGame(new DateTime('-1 hour'));
        $token = $this->tokenForUser($this->stubUser(1, true));

        self::assertSame(VoterInterface::ACCESS_GRANTED, $this->voter->vote($token, $game, [PredictionVoter::CREATE]));
    }

    public function testOwnerCanEditPredictionBeforeMatchStarts(): void
    {
        $user = $this->stubUser(1);
        $prediction = $this->stubPrediction($user, new DateTime('+1 hour'));
        $token = $this->tokenForUser($user);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $this->voter->vote($token, $prediction, [PredictionVoter::EDIT]));
    }

    public function testOwnerCannotEditPredictionAfterMatchStarts(): void
    {
        $user = $this->stubUser(1);
        $prediction = $this->stubPrediction($user, new DateTime('-1 hour'));
        $token = $this->tokenForUser($user);

        self::assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($token, $prediction, [PredictionVoter::EDIT]));
    }

    public function testNonOwnerCannotEditPrediction(): void
    {
        $owner = $this->stubUser(1);
        $otherUser = $this->stubUser(2);
        $prediction = $this->stubPrediction($owner, new DateTime('+1 hour'));
        $token = $this->tokenForUser($otherUser);

        self::assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($token, $prediction, [PredictionVoter::EDIT]));
    }

    public function testAdminCanEditAnyPredictionAfterMatchStarts(): void
    {
        $owner = $this->stubUser(1);
        $admin = $this->stubUser(2, true);
        $prediction = $this->stubPrediction($owner, new DateTime('-1 hour'));
        $token = $this->tokenForUser($admin);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $this->voter->vote($token, $prediction, [PredictionVoter::EDIT]));
    }

    public function testAbstainsOnUnsupportedAttribute(): void
    {
        $game = $this->stubGame(new DateTime('+1 hour'));
        $token = $this->tokenForUser($this->stubUser(1));

        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $this->voter->vote($token, $game, ['SOME_OTHER']));
    }

    private function stubUser(int $id, bool $admin = false): User
    {
        $roles = $admin ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER'];

        $user = $this->createStub(User::class);
        $user->method('getId')->willReturn($id);
        $user->method('getRoles')->willReturn($roles);
        $user->method('hasRole')->willReturnCallback(static fn (string $role) => in_array($role, $roles, true));
        $user->method('getUserIdentifier')->willReturn('user_' . $id);

        return $user;
    }

    private function stubGame(DateTime $playedAt): Game
    {
        $game = $this->createStub(Game::class);
        $game->method('getPlayedAt')->willReturn($playedAt);

        return $game;
    }

    private function stubPrediction(User $owner, DateTime $playedAt): Prediction
    {
        $game = $this->stubGame($playedAt);

        $prediction = $this->createStub(Prediction::class);
        $prediction->method('getUser')->willReturn($owner);
        $prediction->method('getGame')->willReturn($game);

        return $prediction;
    }

    private function tokenForUser(User $user): UsernamePasswordToken
    {
        return new UsernamePasswordToken($user, 'main', $user->getRoles());
    }
}
