<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\Prediction;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PredictionManager
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function save(Prediction $prediction): void
    {
        $this->em->persist($prediction);
        $this->em->flush();
    }
}
