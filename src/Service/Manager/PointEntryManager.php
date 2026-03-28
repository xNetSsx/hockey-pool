<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\Game;
use App\Entity\PointEntry;
use App\Entity\Tournament;
use App\Repository\PointEntryRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Single point of persistence for PointEntry entities.
 *
 * Handles saving, deleting, and flushing — keeps resolvers free of Doctrine.
 */
final readonly class PointEntryManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private PointEntryRepository $pointEntryRepository,
    ) {
    }

    /**
     * Persists a batch of point entries and flushes.
     *
     * @param list<PointEntry> $entries
     */
    public function saveAll(array $entries): void
    {
        foreach ($entries as $entry) {
            $this->em->persist($entry);
        }

        $this->em->flush();
    }

    public function deleteByGame(Game $game): void
    {
        $this->pointEntryRepository->deleteByGame($game);
    }

    public function deleteSpecialBetEntries(Tournament $tournament): void
    {
        $this->pointEntryRepository->deleteSpecialBetEntries($tournament);
    }
}
