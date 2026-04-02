<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\PointEntry;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PointEntryManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * @param list<PointEntry> $entries
     */
    public function saveAll(array $entries): void
    {
        foreach ($entries as $entry) {
            $this->em->persist($entry);
        }

        $this->em->flush();
    }

    /**
     * @param list<PointEntry> $entries
     */
    public function removeAll(array $entries): void
    {
        foreach ($entries as $entry) {
            $this->em->remove($entry);
        }

        $this->em->flush();
    }

    /**
     * Removes old entries and persists new entries in a single flush.
     *
     * @param list<PointEntry> $toRemove
     * @param list<PointEntry> $toAdd
     */
    public function replaceAll(array $toRemove, array $toAdd): void
    {
        foreach ($toRemove as $entry) {
            $this->em->remove($entry);
        }

        foreach ($toAdd as $entry) {
            $this->em->persist($entry);
        }

        if (count($toRemove) > 0 || count($toAdd) > 0) {
            $this->em->flush();
        }
    }
}
