<?php

declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\RuleSet;
use App\Entity\SpecialBetRule;
use App\Entity\Tournament;
use App\Repository\RuleSetRepository;
use App\Repository\SpecialBetRuleRepository;
use Doctrine\ORM\EntityManagerInterface;

use function count;

final readonly class TournamentManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private RuleSetRepository $ruleSetRepository,
        private SpecialBetRuleRepository $specialBetRuleRepository,
    ) {
    }

    public function save(Tournament $tournament): void
    {
        $this->em->persist($tournament);
        $this->em->flush();
    }

    /**
     * @return array{ruleSetCloned: bool, rulesCloned: int}
     */
    public function cloneRulesFrom(Tournament $source, Tournament $target): array
    {
        $ruleSetCloned = $this->cloneRuleSet($source, $target);
        $rulesCloned = $this->cloneSpecialBetRules($source, $target);

        $this->em->flush();

        return [
            'ruleSetCloned' => $ruleSetCloned,
            'rulesCloned' => $rulesCloned,
        ];
    }

    private function cloneRuleSet(Tournament $source, Tournament $target): bool
    {
        if (null !== $this->ruleSetRepository->findByTournament($target)) {
            return false;
        }

        $sourceRuleSet = $this->ruleSetRepository->findByTournament($source);

        if (null === $sourceRuleSet) {
            return false;
        }

        $newRuleSet = new RuleSet();
        $newRuleSet->setTournament($target);
        $newRuleSet->setWinnerBasePoints($sourceRuleSet->getWinnerBasePoints());
        $newRuleSet->setWrongOpponentBonus($sourceRuleSet->getWrongOpponentBonus());
        $newRuleSet->setExactScoreBonus($sourceRuleSet->getExactScoreBonus());
        $newRuleSet->setPrizes($sourceRuleSet->getPrizes());

        $this->em->persist($newRuleSet);

        return true;
    }

    private function cloneSpecialBetRules(Tournament $source, Tournament $target): int
    {
        if (count($this->specialBetRuleRepository->findByTournament($target)) > 0) {
            return 0;
        }

        $sourceRules = $this->specialBetRuleRepository->findByTournament($source);
        $cloned = 0;

        foreach ($sourceRules as $sourceRule) {
            $newRule = new SpecialBetRule();
            $newRule->setTournament($target);
            $newRule->setName($sourceRule->getName());
            $newRule->setValueType($sourceRule->getValueType());
            $newRule->setScoringType($sourceRule->getScoringType());
            $newRule->setPoints($sourceRule->getPoints());
            $newRule->setSortOrder($sourceRule->getSortOrder());

            $this->em->persist($newRule);
            $cloned++;
        }

        return $cloned;
    }
}
