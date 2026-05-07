<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\SpecialBetRule;
use App\Enum\BetValueType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SpecialBetRuleManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private TeamRepository $teamRepository,
    ) {
    }

    public function save(SpecialBetRule $rule): void
    {
        $this->em->persist($rule);
        $this->em->flush();
    }

    public function delete(SpecialBetRule $rule): void
    {
        $this->em->remove($rule);
        $this->em->flush();
    }

    /**
     * @param list<SpecialBetRule> $rules
     * @param array<int|string, string> $rawValues keyed by rule ID
     */
    public function updateActualValues(array $rules, array $rawValues): void
    {
        foreach ($rules as $rule) {
            $rawValue = $rawValues[$rule->getId()] ?? '';

            $rule->setActualTeamValue(null);
            $rule->setActualStringValue(null);
            $rule->setActualIntValue(null);

            if ('' !== $rawValue) {
                match ($rule->getValueType()) {
                    BetValueType::Team => $rule->setActualTeamValue($this->teamRepository->find((int) $rawValue)),
                    BetValueType::String => $rule->setActualStringValue($rawValue),
                    BetValueType::Integer => $rule->setActualIntValue((int) $rawValue),
                };
            }
        }

        $this->em->flush();
    }
}
