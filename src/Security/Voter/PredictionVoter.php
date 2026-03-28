<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\User;
use DateTime;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Controls whether a prediction can be created or edited.
 *
 * Rules:
 *  - Predictions can only be created/edited before the match starts (playedAt).
 *  - Only the prediction owner can edit their prediction.
 *  - Admins can always edit.
 *
 * @extends Voter<string, Game|Prediction>
 */
class PredictionVoter extends Voter
{
    public const string CREATE = 'PREDICTION_CREATE';
    public const string EDIT = 'PREDICTION_EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::CREATE === $attribute && $subject instanceof Game) {
            return true;
        }

        return self::EDIT === $attribute && $subject instanceof Prediction;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (self::CREATE === $attribute) {
            assert($subject instanceof Game);

            return $this->canCreate($subject, $user);
        }

        if (self::EDIT === $attribute) {
            assert($subject instanceof Prediction);

            return $this->canEdit($subject, $user);
        }

        return false;
    }

    private function canCreate(Game $game, User $user): bool
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        return !$this->hasMatchStarted($game);
    }

    private function canEdit(Prediction $prediction, User $user): bool
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        if ($prediction->getUser()->getId() !== $user->getId()) {
            return false;
        }

        return !$this->hasMatchStarted($prediction->getGame());
    }

    private function hasMatchStarted(Game $game): bool
    {
        return $game->getPlayedAt() <= new DateTime();
    }
}
