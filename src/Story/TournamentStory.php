<?php

declare(strict_types=1);

namespace App\Story;

use App\Enum\TournamentStatus;
use App\Factory\TournamentFactory;
use Zenstruck\Foundry\Story;

final class TournamentStory extends Story
{
    private const string RULES = <<<'HTML'
        <h2>Bodování zápasů</h2>
        <ul>
            <li><strong>Správný vítěz</strong> — 1 bod</li>
            <li><strong>Bonus za soupeře</strong> — +0,25 bodu za každého hráče, který tipnul špatně</li>
            <li><strong>Přesné skóre</strong> — +2 body navíc za přesný výsledek</li>
        </ul>
        <h2>Speciální tipy</h2>
        <h3>Medailové pozice (1–3 body)</h3>
        <ul>
            <li><strong>Přesná pozice</strong> — 3 body (např. tipneš zlato pro CAN a CAN vyhraje zlato)</li>
            <li><strong>Tým v top 3</strong> — 1 bod (např. tipneš zlato pro CAN, ale CAN získá bronz)</li>
        </ul>
        <h3>Nejlepší Češi (2 body)</h3>
        <ul>
            <li>Tipuješ 3 nejlepší české hráče turnaje</li>
            <li>Body dostaneš za každého správného hráče bez ohledu na pořadí</li>
        </ul>
        <h3>Číselné tipy (2 body)</h3>
        <ul>
            <li><strong>Počet gólů ČR</strong>, <strong>remízy</strong>, <strong>trestné minuty</strong> apod.</li>
            <li>Body získává hráč (nebo hráči) s nejbližším tipem ke skutečnosti</li>
        </ul>
        <h3>Pořadí skupin (1 bod za pozici)</h3>
        <ul>
            <li>Tipuješ konečné pořadí týmů v základní skupině</li>
            <li>1 bod za každou správně umístěnou pozici</li>
        </ul>
        <h3>Sestupující týmy (2 body)</h3>
        <ul>
            <li>Tipuješ, které týmy sestoupí</li>
            <li>2 body za každý správně tipnutý tým</li>
        </ul>
        <h2>Odměny</h2>
        <ul>
            <li>1. místo — 300 Kč</li>
            <li>2. místo — 150 Kč</li>
            <li>3. místo — 50 Kč</li>
        </ul>
        HTML;

    private const string MANUAL = <<<'HTML'
        <h2>Jak tipovat</h2>
        <ol>
            <li>Přejdi na stránku <strong>Tipy</strong> v hlavním menu</li>
            <li>U každého zápasu klikni na <strong>Tipovat</strong> a zadej skóre</li>
            <li>Tip můžeš měnit kdykoliv až do začátku zápasu</li>
            <li>Po začátku zápasu se tip uzamkne</li>
        </ol>
        <h2>Speciální tipy</h2>
        <ol>
            <li>Na stránce tipů klikni na <strong>Speciální tipy</strong></li>
            <li>Vyplň tipy na medaile, nejlepší Čechy a číselné hodnoty</li>
            <li>Speciální tipy se uzamknou po začátku prvního zápasu turnaje</li>
        </ol>
        <h2>Žebříček</h2>
        <ul>
            <li>Na hlavní stránce vidíš aktuální žebříček a graf průběhu bodů</li>
            <li>Kliknutím na jméno hráče zobrazíš jeho profil s detailními statistikami</li>
            <li>Na stránce <strong>Porovnat</strong> si můžeš porovnat výsledky s ostatními</li>
        </ul>
        <h2>Přepínání turnajů</h2>
        <ul>
            <li>Klikni na název turnaje v horní liště (nebo přejdi na <strong>Turnaje</strong>)</li>
            <li>Vyber turnaj, jehož výsledky chceš zobrazit</li>
        </ul>
        HTML;

    public function build(): void
    {
        $this->addState('oh2022', TournamentFactory::createOne([
            'name' => 'Olympijské hry 2022',
            'year' => 2022,
            'slug' => 'oh-2022',
            'status' => TournamentStatus::Finished,
            'rulesContent' => self::RULES,
            'manualContent' => self::MANUAL,
        ]));

        $this->addState('ms2022', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2022',
            'year' => 2022,
            'slug' => 'ms-2022',
            'status' => TournamentStatus::Finished,
            'rulesContent' => self::RULES,
            'manualContent' => self::MANUAL,
        ]));

        $this->addState('ms2023', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2023',
            'year' => 2023,
            'slug' => 'ms-2023',
            'status' => TournamentStatus::Finished,
            'rulesContent' => self::RULES,
            'manualContent' => self::MANUAL,
        ]));

        $this->addState('ms2024', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2024',
            'year' => 2024,
            'slug' => 'ms-2024',
            'status' => TournamentStatus::Finished,
            'rulesContent' => self::RULES,
            'manualContent' => self::MANUAL,
        ]));

        $this->addState('ms2025', TournamentFactory::createOne([
            'name' => 'Mistrovství světa 2025',
            'year' => 2025,
            'slug' => 'ms-2025',
            'status' => TournamentStatus::Finished,
            'rulesContent' => self::RULES,
            'manualContent' => self::MANUAL,
        ]));

        $this->addState('oh2026', TournamentFactory::createOne([
            'name' => 'Olympijské hry 2026',
            'year' => 2026,
            'slug' => 'oh-2026',
            'status' => TournamentStatus::Finished,
            'rulesContent' => self::RULES,
            'manualContent' => self::MANUAL,
        ]));
    }
}
