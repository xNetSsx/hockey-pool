<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Tournament;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\RuleSetRepository;
use App\Repository\TournamentParticipantRepository;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Controls whether a user can submit special bets for a tournament.
 *
 * Rules:
 *  - Admins are always allowed.
 *  - User must be a participant of the tournament.
 *  - If the rule set has payment settings, the participant must be paid.
 *  - The first match must not have started yet.
 *
 * @extends Voter<string, Tournament>
 */
class SpecialBetVoter extends Voter
{
    public const string SUBMIT = 'SPECIAL_BET_SUBMIT';

    public function __construct(
        private readonly TournamentParticipantRepository $participantRepository,
        private readonly RuleSetRepository $ruleSetRepository,
        private readonly GameRepository $gameRepository,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::SUBMIT === $attribute && $subject instanceof Tournament;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        assert($subject instanceof Tournament);

        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        $participant = $this->participantRepository->findParticipant($user, $subject);

        if (null === $participant) {
            return false;
        }

        $ruleSet = $this->ruleSetRepository->findByTournament($subject);
        if (null !== $ruleSet && $ruleSet->hasPaymentSettings() && !$participant->isPaid()) {
            return false;
        }

        $firstMatchDate = $this->gameRepository->findFirstMatchDate($subject);

        return null === $firstMatchDate || $firstMatchDate > new DateTimeImmutable();
    }
}
