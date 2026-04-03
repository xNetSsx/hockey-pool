<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260403000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed rules and manual content for all tournaments';
    }

    public function up(Schema $schema): void
    {
        $rules = <<<'HTML'
<h1>Pravidla Hockey Pool</h1>
<h2>Základní princip</h2>
<p>V Hockey Pool tipuješ výsledky hokejových zápasů. Za každý správně uhádnutý výsledek získáváš body. Na konci turnaje vyhrává hráč s nejvyšším celkovým skóre.</p>
<h2>Tipování zápasů</h2>
<p>Každý zápas lze tipovat až do jeho začátku — v okamžiku výkopu se tip uzamkne a nelze ho změnit. Zadáváš přesné skóre, tedy počet gólů domácích a hostů.</p>
<h2>Bodování zápasů</h2>
<h3>Základ — správný vítěz</h3>
<p>Za uhodnutí vítěze zápasu (bez ohledu na přesné skóre) dostaneš <strong>1 bod</strong>.</p>
<h3>Bonus — soupeř se spletl</h3>
<p>Za každého hráče, který tipoval špatně, dostaneš <strong>0,25 bodu</strong> navíc. Čím více hráčů šlápne vedle, tím více na tom vyděláš.</p>
<h3>Bonus — přesné skóre</h3>
<p>Pokud uhádneš přesné skóre (například 3:1), dostaneš dalších <strong>2 body</strong>.</p>
<h2>Příklad bodování</h2>
<p>Zápas končí 4:2. Z 8 hráčů tipují 3 správně, 5 špatně.</p>
<ul>
<li>Základ za správného vítěze: <strong>1 bod</strong></li>
<li>Bonus za soupeře: 5 × 0,25 = <strong>1,25 bodu</strong></li>
<li>Celkem za správný výsledek (ne přesné skóre): <strong>2,25 bodu</strong></li>
<li>Pokud jsi tipoval přesně 4:2, dostáváš navíc <strong>2 body</strong> → celkem <strong>4,25 bodu</strong></li>
</ul>
<h2>Speciální tipy</h2>
<p>Vedle tipování zápasů existují bonusové otázky na celý turnaj. Každá má vlastní bodovou hodnotu a způsob vyhodnocení:</p>
<ul>
<li><strong>Přesná shoda</strong> — musíš trefit přesnou odpověď (tým, hráč, …).</li>
<li><strong>Nejblíže</strong> — hádáš číslo (např. počet gólů); vyhrává ten, kdo je nejblíže skutečnosti. Při shodné vzdálenosti bodují všichni nejbližší.</li>
<li><strong>Pódium</strong> — hádáš pořadí na medailových místech. Za správnou pozici plný počet bodů, za správný tým na špatné pozici 1 bod.</li>
<li><strong>Jakákoliv shoda</strong> — odpověď musí být ze seznamu platných možností.</li>
</ul>
<h2>Uzávěrka tipů</h2>
<p>Speciální tipy lze zadávat do začátku prvního zápasu turnaje. Zápasové tipy se uzavírají individuálně s každým zápasem.</p>
HTML;

        $manual = <<<'HTML'
<h1>Jak na Hockey Pool</h1>
<p>Vítej v Hockey Pool! Tato příručka tě provede aplikací krok za krokem.</p>
<h2>Moje tipy</h2>
<p>Stránka <strong>Tipy</strong> je tvoje hlavní základna. Vidíš zde všechny zápasy turnaje seřazené podle fáze a data. U každého zápasu je tvůj aktuální tip a tlačítko pro jeho zadání nebo změnu.</p>
<h2>Zadání tipu</h2>
<p>Klikni na <strong>Tipovat</strong> u libovolného zápasu, zadej počet gólů domácích a hostů a ulož. Tip lze kdykoli změnit, dokud zápas nezačne — pak se uzamkne. Odpočet vedle tlačítka ukazuje, za jak dlouho k uzamčení dojde.</p>
<h2>Fáze turnaje</h2>
<p>Zápasy jsou rozděleny do skupinové fáze, čtvrtfinále, semifinále a finále. Každá fáze je zvlášť označena. Tipy lze zadávat pro všechny fáze najednou — nemusíš čekat na konec skupiny.</p>
<h2>Zápas</h2>
<p>Každý řádek představuje jeden zápas. Vidíš čas výkopu, vlajky a kódy týmů a výsledné skóre (pokud byl zápas odehrán). Barevný odznak vedle tipu ukazuje jeho přesnost:</p>
<ul>
<li><strong>Zelená</strong> — přesné skóre</li>
<li><strong>Modrá</strong> — správný vítěz</li>
<li><strong>Červená</strong> — špatný tip</li>
</ul>
<p>Po začátku zápasu se zobrazí tlačítko <strong>Tipy hráčů</strong> — klikni a uvidíš, co tipovali ostatní včetně rozpisu bodů.</p>
<h2>Speciální tipy</h2>
<p>Klikni na <strong>Moje speciální tipy</strong> a dostaneš se na stránku s bonusovými otázkami na celý turnaj. Každá otázka má jiný typ odpovědi — tým, číslo nebo text. Vyplň vše co znáš a ulož. Jde to měnit až do začátku prvního zápasu.</p>
<h2>Tipy ostatních</h2>
<p>Na stránce <strong>Tipy ostatních</strong> vidíš speciální tipy všech hráčů najednou — ideální na trochu průzkumu soupeřů 😄</p>
<h2>Žebříček a statistiky</h2>
<p>Na hlavní stránce najdeš průběžný žebříček, graf vývoje bodů po dnech a přehled nadcházejících zápasů. Graf ukazuje, jak se body vyvíjely v průběhu celého turnaje.</p>
<h2>Výsledky po zápase</h2>
<p>Po odehrání zápasu klikni na <strong>Tipy hráčů</strong> a uvidíš tabulku s tipy všech hráčů, rozpisem bodů (základ, bonus za soupeře, bonus za přesné skóre) a celkovým součtem za tento zápas.</p>
HTML;

        $this->addSql(
            'UPDATE tournament SET rules_content = :rules WHERE rules_content IS NULL',
            ['rules' => $rules],
        );

        $this->addSql(
            'UPDATE tournament SET manual_content = :manual WHERE manual_content IS NULL',
            ['manual' => $manual],
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE tournament SET rules_content = NULL, manual_content = NULL');
    }
}
