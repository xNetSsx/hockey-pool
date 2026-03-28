<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Story\GameStory;
use App\Story\PredictionStory;
use App\Story\RuleSetStory;
use App\Story\SpecialBetRuleStory;
use App\Story\SpecialBetStory;
use App\Story\TeamStory;
use App\Story\TournamentStory;
use App\Story\UserStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TeamStory::load();
        UserStory::load();
        TournamentStory::load();
        RuleSetStory::load();
        GameStory::load();
        PredictionStory::load();
        SpecialBetRuleStory::load();
        SpecialBetStory::load();
    }
}
