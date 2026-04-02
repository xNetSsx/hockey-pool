<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User;
use App\Service\Provider\ActiveTournamentProvider;
use App\Service\Provider\PredictionStatusProvider;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Exposes global Twig variables:
 *  - prediction_status: missing predictions info
 *  - current_tournament: the active/selected tournament
 */
final class PredictionStatusExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly PredictionStatusProvider $predictionStatusProvider,
        private readonly ActiveTournamentProvider $activeTournamentProvider,
    ) {
    }

    /** @return array<string, mixed> */
    public function getGlobals(): array
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return [
                'prediction_status' => null,
                'current_tournament' => null,
            ];
        }

        return [
            'prediction_status' => $this->predictionStatusProvider->getStatus($user),
            'current_tournament' => $this->activeTournamentProvider->getActiveTournament(),
        ];
    }
}
