<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\SpecialBet;
use App\Entity\SpecialBetRule;
use App\Entity\User;
use App\Enum\BetValueType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SpecialBetManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private TeamRepository $teamRepository,
    ) {
    }

    public function save(SpecialBet $bet): void
    {
        $this->em->persist($bet);
        $this->em->flush();
    }

    /**
     * @param list<SpecialBet> $bets
     */
    public function saveAll(array $bets): void
    {
        foreach ($bets as $bet) {
            $this->em->persist($bet);
        }

        $this->em->flush();
    }

    public function setBetValue(SpecialBet $bet, SpecialBetRule $rule, string $rawValue): void
    {
        $bet->setTeamValue(null);
        $bet->setStringValue(null);
        $bet->setIntValue(null);

        match ($rule->getValueType()) {
            BetValueType::Team => $bet->setTeamValue($this->teamRepository->find((int) $rawValue)),
            BetValueType::String => $bet->setStringValue($rawValue),
            BetValueType::Integer => $bet->setIntValue((int) $rawValue),
        };
    }

    /**
     * @param User $user
     * @param list<SpecialBetRule> $rules
     * @param array<int, SpecialBet|null> $existingBets
     * @param array<int|string, string> $rawValues
     */
    public function updateBets(User $user, array $rules, array $existingBets, array $rawValues): void
    {
        $bets = [];

        foreach ($rules as $rule) {
            $rawValue = $rawValues[$rule->getId()] ?? '';

            if ('' === $rawValue) {
                continue;
            }

            $bet = $existingBets[$rule->getId()] ?? null;

            if (null === $bet) {
                $bet = new SpecialBet();
                $bet->setUser($user);
                $bet->setRule($rule);
            }

            $this->setBetValue($bet, $rule, $rawValue);
            $bets[] = $bet;
        }

        if (count($bets) > 0) {
            $this->saveAll($bets);
        }
    }
}
