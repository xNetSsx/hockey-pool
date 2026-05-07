<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\Game;
use App\Entity\Prediction;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\PredictionRepository;
use App\Security\Voter\PredictionVoter;
use App\Service\Manager\PredictionManager;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class InlineTip
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $gameId = 0;

    #[LiveProp(writable: true)]
    public int $homeScore = 0;

    #[LiveProp(writable: true)]
    public int $awayScore = 0;

    #[LiveProp]
    public bool $editing = false;

    #[LiveProp]
    public bool $saved = false;

    #[LiveProp]
    public bool $hasPrediction = false;

    #[LiveProp]
    public string $errorMessage = '';

    #[LiveProp]
    public string $deadline = '';

    #[LiveProp]
    public string $czechSide = '';

    #[LiveProp]
    public int $treasonStage = 0;

    public function __construct(
        private readonly Security $security,
        private readonly GameRepository $gameRepository,
        private readonly PredictionRepository $predictionRepository,
        private readonly PredictionManager $predictionManager,
    ) {
    }

    /**
     * Called after LiveProp values are hydrated from the template.
     */
    public function mount(int $gameId): void
    {
        $this->gameId = $gameId;

        $user = $this->getCurrentUser();
        $game = $this->getGame();

        $prediction = $this->predictionRepository->findOneBy([
            'user' => $user,
            'game' => $game,
        ]);

        $this->deadline = $game->getPlayedAt()->format('c');

        if ('CZE' === $game->getHomeTeam()->getCode()) {
            $this->czechSide = 'home';
        } elseif ('CZE' === $game->getAwayTeam()->getCode()) {
            $this->czechSide = 'away';
        }

        if (null !== $prediction) {
            $this->homeScore = $prediction->getHomeScore();
            $this->awayScore = $prediction->getAwayScore();
            $this->hasPrediction = true;
        }
    }

    #[LiveAction]
    public function open(): void
    {
        $this->editing = true;
        $this->saved = false;
        $this->errorMessage = '';
    }

    #[LiveAction]
    public function bump(#[LiveArg] string $side, #[LiveArg] int $delta): void
    {
        if ('home' === $side) {
            $this->homeScore = max(0, min(20, $this->homeScore + $delta));
        } elseif ('away' === $side) {
            $this->awayScore = max(0, min(20, $this->awayScore + $delta));
        }
    }

    #[LiveAction]
    public function save(): void
    {
        if ('' !== $this->czechSide && 0 === $this->treasonStage && $this->isBettingAgainstCzech()) {
            $this->treasonStage = 1;

            return;
        }

        $this->doSave();
    }

    #[LiveAction]
    public function escalateTreason(): void
    {
        $this->treasonStage = 2;
    }

    #[LiveAction]
    public function cancelTreason(): void
    {
        $this->treasonStage = 0;
    }

    #[LiveAction]
    public function confirmTreason(): void
    {
        $this->treasonStage = 0;
        $this->doSave();
    }

    private function isBettingAgainstCzech(): bool
    {
        if ('home' === $this->czechSide) {
            return $this->awayScore > $this->homeScore;
        }

        if ('away' === $this->czechSide) {
            return $this->homeScore > $this->awayScore;
        }

        return false;
    }

    private function doSave(): void
    {
        $game = $this->getGame();
        $user = $this->getCurrentUser();

        if (!$this->security->isGranted(PredictionVoter::CREATE, $game)) {
            $this->errorMessage = 'Nejsi účastníkem turnaje nebo nemáš zaplaceno.';

            return;
        }

        if ($this->homeScore === $this->awayScore) {
            $this->errorMessage = 'Remíza není povolena.';

            return;
        }

        $prediction = $this->predictionRepository->findOneBy([
            'user' => $user,
            'game' => $game,
        ]);

        if (null === $prediction) {
            $prediction = new Prediction();
            $prediction->setUser($user);
            $prediction->setGame($game);
        }

        $prediction->setHomeScore($this->homeScore);
        $prediction->setAwayScore($this->awayScore);
        $prediction->setUpdatedAt(new DateTimeImmutable());

        $this->predictionManager->save($prediction);

        $this->editing = false;
        $this->saved = true;
        $this->hasPrediction = true;
        $this->errorMessage = '';
    }

    #[LiveAction]
    public function cancel(): void
    {
        $user = $this->getCurrentUser();
        $game = $this->getGame();

        $prediction = $this->predictionRepository->findOneBy([
            'user' => $user,
            'game' => $game,
        ]);

        if (null !== $prediction) {
            $this->homeScore = $prediction->getHomeScore();
            $this->awayScore = $prediction->getAwayScore();
        } else {
            $this->homeScore = 0;
            $this->awayScore = 0;
        }

        $this->editing = false;
        $this->saved = false;
        $this->errorMessage = '';
    }

    private function getGame(): Game
    {
        $game = $this->gameRepository->find($this->gameId);

        if (null === $game) {
            throw new NotFoundHttpException(sprintf('Game %d not found.', $this->gameId));
        }

        return $game;
    }

    private function getCurrentUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedHttpException('User not authenticated.');
        }

        return $user;
    }
}
