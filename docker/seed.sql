--
-- PostgreSQL database dump
--

\restrict pLKRAKRa0TaeHrypZL924ulBhz2EYF0yrUzG54gHhkZtQYASQ7fAek4CK7FifY0

-- Dumped from database version 16.13
-- Dumped by pg_dump version 16.13

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: team; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.team VALUES (25, 'Česká republika', 'CZE', '🇨🇿');
INSERT INTO public.team VALUES (26, 'Slovensko', 'SVK', '🇸🇰');
INSERT INTO public.team VALUES (27, 'Finsko', 'FIN', '🇫🇮');
INSERT INTO public.team VALUES (28, 'Švédsko', 'SWE', '🇸🇪');
INSERT INTO public.team VALUES (29, 'Itálie', 'ITA', '🇮🇹');
INSERT INTO public.team VALUES (30, 'Švýcarsko', 'SUI', '🇨🇭');
INSERT INTO public.team VALUES (31, 'Francie', 'FRA', '🇫🇷');
INSERT INTO public.team VALUES (32, 'Kanada', 'CAN', '🇨🇦');
INSERT INTO public.team VALUES (33, 'Lotyšsko', 'LAT', '🇱🇻');
INSERT INTO public.team VALUES (34, 'USA', 'USA', '🇺🇸');
INSERT INTO public.team VALUES (35, 'Německo', 'GER', '🇩🇪');
INSERT INTO public.team VALUES (36, 'Dánsko', 'DEN', '🇩🇰');
INSERT INTO public.team VALUES (37, 'Rusko', 'RUS', NULL);
INSERT INTO public.team VALUES (38, 'Velká Británie', 'GBR', '🇬🇧');
INSERT INTO public.team VALUES (39, 'Norsko', 'NOR', '🇳🇴');
INSERT INTO public.team VALUES (40, 'Rakousko', 'AUS', '🇦🇹');
INSERT INTO public.team VALUES (41, 'Kazachstán', 'KAZ', '🇰🇿');
INSERT INTO public.team VALUES (42, 'Polsko', 'POL', '🇵🇱');
INSERT INTO public.team VALUES (43, 'Slovinsko', 'SLO', '🇸🇮');
INSERT INTO public.team VALUES (44, 'Maďarsko', 'HUN', '🇭🇺');


--
-- Data for Name: tournament; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.tournament VALUES (12, 'Olympijské hry 2022', 2022, 'oh-2022', 'finished', '2026-04-02 22:20:10', '<h2>Bodování zápasů</h2>
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
</ul>', '<h2>Jak tipovat</h2>
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
</ul>');
INSERT INTO public.tournament VALUES (13, 'Mistrovství světa 2022', 2022, 'ms-2022', 'finished', '2026-04-02 22:20:10', '<h2>Bodování zápasů</h2>
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
</ul>', '<h2>Jak tipovat</h2>
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
</ul>');
INSERT INTO public.tournament VALUES (14, 'Mistrovství světa 2023', 2023, 'ms-2023', 'finished', '2026-04-02 22:20:10', '<h2>Bodování zápasů</h2>
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
</ul>', '<h2>Jak tipovat</h2>
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
</ul>');
INSERT INTO public.tournament VALUES (15, 'Mistrovství světa 2024', 2024, 'ms-2024', 'finished', '2026-04-02 22:20:10', '<h2>Bodování zápasů</h2>
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
</ul>', '<h2>Jak tipovat</h2>
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
</ul>');
INSERT INTO public.tournament VALUES (16, 'Mistrovství světa 2025', 2025, 'ms-2025', 'finished', '2026-04-02 22:20:10', '<h2>Bodování zápasů</h2>
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
</ul>', '<h2>Jak tipovat</h2>
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
</ul>');
INSERT INTO public.tournament VALUES (17, 'Olympijské hry 2026', 2026, 'oh-2026', 'finished', '2026-04-02 22:20:10', '<h2>Bodování zápasů</h2>
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
</ul>', '<h2>Jak tipovat</h2>
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
</ul>');


--
-- Data for Name: match; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.match VALUES (629, 17, 26, 27, 'group_stage', '2026-02-11 16:40:00', 4, 1, true);
INSERT INTO public.match VALUES (630, 17, 28, 29, 'group_stage', '2026-02-11 21:10:00', 5, 2, true);
INSERT INTO public.match VALUES (631, 17, 30, 31, 'group_stage', '2026-02-12 12:10:00', 4, 0, true);
INSERT INTO public.match VALUES (632, 17, 25, 32, 'group_stage', '2026-02-12 16:40:00', 0, 5, true);
INSERT INTO public.match VALUES (633, 17, 33, 34, 'group_stage', '2026-02-12 21:10:00', 1, 5, true);
INSERT INTO public.match VALUES (634, 17, 35, 36, 'group_stage', '2026-02-12 21:10:00', 3, 1, true);
INSERT INTO public.match VALUES (635, 17, 27, 28, 'group_stage', '2026-02-13 12:10:00', 4, 1, true);
INSERT INTO public.match VALUES (636, 17, 29, 26, 'group_stage', '2026-02-13 12:10:00', 2, 3, true);
INSERT INTO public.match VALUES (637, 17, 31, 25, 'group_stage', '2026-02-13 16:40:00', 3, 6, true);
INSERT INTO public.match VALUES (638, 17, 32, 30, 'group_stage', '2026-02-13 21:10:00', 5, 1, true);
INSERT INTO public.match VALUES (639, 17, 28, 26, 'group_stage', '2026-02-14 12:10:00', 5, 3, true);
INSERT INTO public.match VALUES (640, 17, 35, 33, 'group_stage', '2026-02-14 12:10:00', 3, 4, true);
INSERT INTO public.match VALUES (641, 17, 27, 29, 'group_stage', '2026-02-14 16:40:00', 11, 0, true);
INSERT INTO public.match VALUES (642, 17, 34, 36, 'group_stage', '2026-02-14 21:10:00', 6, 3, true);
INSERT INTO public.match VALUES (643, 17, 30, 25, 'group_stage', '2026-02-15 12:10:00', 4, 3, true);
INSERT INTO public.match VALUES (644, 17, 32, 31, 'group_stage', '2026-02-15 16:40:00', 10, 2, true);
INSERT INTO public.match VALUES (645, 17, 36, 33, 'group_stage', '2026-02-15 19:10:00', 4, 2, true);
INSERT INTO public.match VALUES (646, 17, 34, 35, 'group_stage', '2026-02-15 21:10:00', 5, 1, true);
INSERT INTO public.match VALUES (647, 17, 30, 29, 'quarterfinal', '2026-02-17 12:10:00', 3, 0, true);
INSERT INTO public.match VALUES (648, 17, 35, 31, 'quarterfinal', '2026-02-17 12:10:00', 5, 1, true);
INSERT INTO public.match VALUES (649, 17, 25, 36, 'quarterfinal', '2026-02-17 16:40:00', 3, 2, true);
INSERT INTO public.match VALUES (650, 17, 28, 33, 'quarterfinal', '2026-02-17 21:10:00', 5, 1, true);
INSERT INTO public.match VALUES (651, 17, 26, 35, 'quarterfinal', '2026-02-18 12:10:00', 6, 2, true);
INSERT INTO public.match VALUES (652, 17, 32, 25, 'quarterfinal', '2026-02-18 14:10:00', 4, 3, true);
INSERT INTO public.match VALUES (653, 17, 27, 30, 'quarterfinal', '2026-02-18 16:40:00', 3, 2, true);
INSERT INTO public.match VALUES (654, 17, 34, 28, 'quarterfinal', '2026-02-18 21:10:00', 2, 1, true);
INSERT INTO public.match VALUES (655, 17, 32, 27, 'semifinal', '2026-02-20 16:40:00', 3, 2, true);
INSERT INTO public.match VALUES (656, 17, 34, 26, 'semifinal', '2026-02-20 21:10:00', 6, 2, true);
INSERT INTO public.match VALUES (657, 17, 26, 27, 'bronze_medal', '2026-02-21 20:40:00', 1, 6, true);
INSERT INTO public.match VALUES (658, 17, 32, 34, 'gold_medal', '2026-02-22 14:10:00', 1, 2, true);


--
-- Data for Name: special_bet_rule; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.special_bet_rule VALUES (108, 12, 27, 'Zlatá medaile', 'team', 'exact_match', 3, 1, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (109, 12, 37, 'Stříbrná medaile', 'team', 'exact_match', 3, 2, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (110, 12, 26, 'Bronzová medaile', 'team', 'exact_match', 3, 3, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (111, 12, NULL, 'Nejlepší Čech #1', 'string', 'any_match', 2, 4, 'Klok', NULL, false);
INSERT INTO public.special_bet_rule VALUES (112, 12, NULL, 'Nejlepší Čech #2', 'string', 'any_match', 2, 5, 'Krejčí', NULL, false);
INSERT INTO public.special_bet_rule VALUES (113, 12, NULL, 'Nejlepší Čech #3', 'string', 'any_match', 2, 6, 'Červenka', NULL, false);
INSERT INTO public.special_bet_rule VALUES (114, 13, 27, 'Zlatá medaile', 'team', 'podium', 3, 1, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (115, 13, 32, 'Stříbrná medaile', 'team', 'podium', 3, 2, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (116, 13, 25, 'Bronzová medaile', 'team', 'podium', 3, 3, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (117, 13, NULL, 'Nejlepší Čech #1', 'string', 'any_match', 2, 4, 'Krejčí', NULL, false);
INSERT INTO public.special_bet_rule VALUES (118, 13, NULL, 'Nejlepší Čech #2', 'string', 'any_match', 2, 5, 'Červenka', NULL, false);
INSERT INTO public.special_bet_rule VALUES (119, 13, NULL, 'Nejlepší Čech #3', 'string', 'any_match', 2, 6, 'Vejmelka', NULL, false);
INSERT INTO public.special_bet_rule VALUES (120, 13, NULL, 'Počet gólů české reprezentace', 'integer', 'closest', 2, 7, NULL, 32, false);
INSERT INTO public.special_bet_rule VALUES (121, 13, 29, 'Sestupující tým 1', 'team', 'exact_match', 2, 8, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (122, 13, 38, 'Sestupující tým 2', 'team', 'exact_match', 2, 9, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (123, 14, 32, 'Zlatá medaile', 'team', 'podium', 3, 1, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (124, 14, 35, 'Stříbrná medaile', 'team', 'podium', 3, 2, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (125, 14, 33, 'Bronzová medaile', 'team', 'podium', 3, 3, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (126, 14, NULL, 'Nejlepší Čech #1', 'string', 'any_match', 2, 4, 'Červenka', NULL, false);
INSERT INTO public.special_bet_rule VALUES (127, 14, NULL, 'Nejlepší Čech #2', 'string', 'any_match', 2, 5, 'Kubalík', NULL, false);
INSERT INTO public.special_bet_rule VALUES (128, 14, NULL, 'Nejlepší Čech #3', 'string', 'any_match', 2, 6, 'Kempný', NULL, false);
INSERT INTO public.special_bet_rule VALUES (129, 14, NULL, 'Počet gólů české reprezentace', 'integer', 'closest', 2, 7, NULL, 22, false);
INSERT INTO public.special_bet_rule VALUES (130, 14, NULL, 'Počet zápasů rozhodnutých na nájezdy', 'integer', 'closest', 2, 8, NULL, 6, false);
INSERT INTO public.special_bet_rule VALUES (131, 14, 43, 'Sestupující tým 1', 'team', 'exact_match', 2, 9, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (132, 14, 44, 'Sestupující tým 2', 'team', 'exact_match', 2, 10, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (133, 15, 25, 'Zlatá medaile', 'team', 'podium', 3, 1, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (134, 15, 30, 'Stříbrná medaile', 'team', 'podium', 3, 2, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (135, 15, 28, 'Bronzová medaile', 'team', 'podium', 3, 3, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (136, 15, NULL, 'Nejlepší Čech #1', 'string', 'any_match', 2, 4, 'Červenka', NULL, false);
INSERT INTO public.special_bet_rule VALUES (137, 15, NULL, 'Nejlepší Čech #2', 'string', 'any_match', 2, 5, 'Kubalík', NULL, false);
INSERT INTO public.special_bet_rule VALUES (138, 15, NULL, 'Nejlepší Čech #3', 'string', 'any_match', 2, 6, 'Sedlák', NULL, false);
INSERT INTO public.special_bet_rule VALUES (139, 15, 32, 'Pořadí skupiny A - 1. místo', 'team', 'exact_match', 1, 7, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (140, 15, 30, 'Pořadí skupiny A - 2. místo', 'team', 'exact_match', 1, 8, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (141, 15, 25, 'Pořadí skupiny A - 3. místo', 'team', 'exact_match', 1, 9, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (142, 15, 27, 'Pořadí skupiny A - 4. místo', 'team', 'exact_match', 1, 10, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (143, 15, 40, 'Pořadí skupiny A - 5. místo', 'team', 'exact_match', 1, 11, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (144, 15, 39, 'Pořadí skupiny A - 6. místo', 'team', 'exact_match', 1, 12, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (145, 15, 36, 'Pořadí skupiny A - 7. místo', 'team', 'exact_match', 1, 13, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (146, 15, 38, 'Pořadí skupiny A - 8. místo', 'team', 'exact_match', 1, 14, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (147, 15, 28, 'Pořadí skupiny B - 1. místo', 'team', 'exact_match', 1, 15, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (148, 15, 34, 'Pořadí skupiny B - 2. místo', 'team', 'exact_match', 1, 16, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (149, 15, 35, 'Pořadí skupiny B - 3. místo', 'team', 'exact_match', 1, 17, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (150, 15, 26, 'Pořadí skupiny B - 4. místo', 'team', 'exact_match', 1, 18, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (151, 15, 33, 'Pořadí skupiny B - 5. místo', 'team', 'exact_match', 1, 19, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (152, 15, 41, 'Pořadí skupiny B - 6. místo', 'team', 'exact_match', 1, 20, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (153, 15, 31, 'Pořadí skupiny B - 7. místo', 'team', 'exact_match', 1, 21, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (154, 15, 42, 'Pořadí skupiny B - 8. místo', 'team', 'exact_match', 1, 22, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (155, 15, NULL, 'Počet gólů české reprezentace', 'integer', 'closest', 2, 23, NULL, 37, false);
INSERT INTO public.special_bet_rule VALUES (156, 15, NULL, 'Remízy v základní době', 'integer', 'closest', 2, 24, NULL, 10, false);
INSERT INTO public.special_bet_rule VALUES (157, 15, NULL, 'Trestné minuty Gudase', 'integer', 'closest', 2, 25, NULL, 18, false);
INSERT INTO public.special_bet_rule VALUES (158, 16, 34, 'Zlatá medaile', 'team', 'podium', 3, 1, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (159, 16, 30, 'Stříbrná medaile', 'team', 'podium', 3, 2, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (160, 16, 28, 'Bronzová medaile', 'team', 'podium', 3, 3, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (161, 16, NULL, 'Nejlepší Čech #1', 'string', 'any_match', 2, 4, 'Pastrňák', NULL, false);
INSERT INTO public.special_bet_rule VALUES (162, 16, NULL, 'Nejlepší Čech #2', 'string', 'any_match', 2, 5, 'Červenka', NULL, false);
INSERT INTO public.special_bet_rule VALUES (163, 16, NULL, 'Nejlepší Čech #3', 'string', 'any_match', 2, 6, 'Sedlák', NULL, false);
INSERT INTO public.special_bet_rule VALUES (164, 16, 32, 'Pořadí skupiny A - 1. místo', 'team', 'exact_match', 1, 7, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (165, 16, 28, 'Pořadí skupiny A - 2. místo', 'team', 'exact_match', 1, 8, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (166, 16, 27, 'Pořadí skupiny A - 3. místo', 'team', 'exact_match', 1, 9, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (167, 16, 40, 'Pořadí skupiny A - 4. místo', 'team', 'exact_match', 1, 10, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (168, 16, 33, 'Pořadí skupiny A - 5. místo', 'team', 'exact_match', 1, 11, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (169, 16, 26, 'Pořadí skupiny A - 6. místo', 'team', 'exact_match', 1, 12, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (170, 16, 43, 'Pořadí skupiny A - 7. místo', 'team', 'exact_match', 1, 13, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (171, 16, 31, 'Pořadí skupiny A - 8. místo', 'team', 'exact_match', 1, 14, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (172, 16, 30, 'Pořadí skupiny B - 1. místo', 'team', 'exact_match', 1, 15, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (173, 16, 34, 'Pořadí skupiny B - 2. místo', 'team', 'exact_match', 1, 16, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (174, 16, 25, 'Pořadí skupiny B - 3. místo', 'team', 'exact_match', 1, 17, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (175, 16, 36, 'Pořadí skupiny B - 4. místo', 'team', 'exact_match', 1, 18, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (176, 16, 35, 'Pořadí skupiny B - 5. místo', 'team', 'exact_match', 1, 19, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (177, 16, 39, 'Pořadí skupiny B - 6. místo', 'team', 'exact_match', 1, 20, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (178, 16, 44, 'Pořadí skupiny B - 7. místo', 'team', 'exact_match', 1, 21, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (179, 16, 41, 'Pořadí skupiny B - 8. místo', 'team', 'exact_match', 1, 22, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (180, 16, NULL, 'Počet gólů české reprezentace', 'integer', 'closest', 2, 23, NULL, 37, false);
INSERT INTO public.special_bet_rule VALUES (181, 16, NULL, 'Remízy v základní době', 'integer', 'closest', 2, 24, NULL, 8, false);
INSERT INTO public.special_bet_rule VALUES (182, 17, 34, 'Zlatá medaile', 'team', 'podium', 3, 1, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (183, 17, 32, 'Stříbrná medaile', 'team', 'podium', 3, 2, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (184, 17, 27, 'Bronzová medaile', 'team', 'podium', 3, 3, NULL, NULL, false);
INSERT INTO public.special_bet_rule VALUES (185, 17, NULL, 'Nejlepší Čech #1', 'string', 'any_match', 2, 4, 'Pastrňák', NULL, false);
INSERT INTO public.special_bet_rule VALUES (186, 17, NULL, 'Nejlepší Čech #2', 'string', 'any_match', 2, 5, 'Nečas', NULL, false);
INSERT INTO public.special_bet_rule VALUES (187, 17, NULL, 'Nejlepší Čech #3', 'string', 'any_match', 2, 6, 'Červenka', NULL, false);
INSERT INTO public.special_bet_rule VALUES (188, 17, NULL, 'Celkem gólů ČR', 'integer', 'closest', 2, 7, NULL, 15, false);
INSERT INTO public.special_bet_rule VALUES (189, 17, NULL, 'Remízy v základní době', 'integer', 'closest', 2, 8, NULL, 5, false);
INSERT INTO public.special_bet_rule VALUES (190, 17, NULL, 'Trestné minuty Gudase', 'integer', 'closest', 2, 9, NULL, 4, false);


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public."user" VALUES (18, 'Ondra', NULL, '$2y$13$CoDn8SDxnpfn8mf/TV8YxuQjqk58oochz1xDIDS4nErfLXAhHN5Pe', '["ROLE_USER","ROLE_ADMIN"]', NULL);
INSERT INTO public."user" VALUES (19, 'Táda', NULL, '$2y$13$TAiJsLKDIL.yAfoPJAltVORejfhWEtUwZboYOjz2hskxZp/VP3ur2', '["ROLE_USER","ROLE_ADMIN"]', NULL);
INSERT INTO public."user" VALUES (20, 'Martin', NULL, '$2y$13$B4JvCjGsXWsZ9j7g3zRqieAwrwqgzjw/DC56knKpJmz4CVW1RUC5i', '["ROLE_USER"]', NULL);
INSERT INTO public."user" VALUES (21, 'Pavel', NULL, '$2y$13$0l9EHnCgNHXDNT2b1OdWRuo6AqWzgC7zaIE85qx5sUc/hvqubAj/a', '["ROLE_USER"]', NULL);
INSERT INTO public."user" VALUES (22, 'Váca', NULL, '$2y$13$fWvu9ihdEwFb9iUIprfDx.i808XUER8Ldpk8H77zgze1jtVylnpQC', '["ROLE_USER"]', NULL);
INSERT INTO public."user" VALUES (23, 'Kuba', NULL, '$2y$13$EC4eWYtW4TZv0HQ6SewWEO4R5UZ0gviOyEErKSE6yfV6GsG8EmqFy', '["ROLE_USER"]', NULL);
INSERT INTO public."user" VALUES (24, 'Mééča', NULL, '$2y$13$BQuKzMFhmbQl7wcD3oKiZe3xLZ/bBRB/HbmtMdfpninpb03FvWT8e', '["ROLE_USER"]', NULL);
INSERT INTO public."user" VALUES (25, 'Honza S', NULL, '$2y$13$tnOkc/C9ZU90cHimfPhhsO/rBZ9d0w6Ju4xzQdC2GKQ8P.AkrZTaO', '["ROLE_USER","ROLE_ADMIN"]', NULL);
INSERT INTO public."user" VALUES (26, 'Mates', NULL, '$2y$13$wBIObc1tgdpjbibT1.44i.94oc1Jpzn0xcXTJnXq3zf03M3wzIvjO', '["ROLE_USER"]', NULL);
INSERT INTO public."user" VALUES (27, 'Fanda', NULL, '$2y$13$KPhOxG3OBR5VVjhr2EefFeraq8CehAVfHW00kcaTjiBL4/gavPYT2', '["ROLE_USER"]', NULL);


--
-- Data for Name: point_entry; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: prediction; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.prediction VALUES (5158, 18, 629, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5159, 19, 629, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5160, 20, 629, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5161, 21, 629, 2, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5162, 22, 629, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5163, 23, 629, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5164, 24, 629, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5165, 25, 629, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5166, 26, 629, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5167, 27, 629, 2, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5168, 18, 630, 7, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5169, 19, 630, 8, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5170, 20, 630, 7, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5171, 21, 630, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5172, 22, 630, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5173, 23, 630, 10, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5174, 24, 630, 6, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5175, 25, 630, 5, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5176, 26, 630, 4, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5177, 27, 630, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5178, 18, 631, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5179, 19, 631, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5180, 20, 631, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5181, 21, 631, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5182, 22, 631, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5183, 23, 631, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5184, 24, 631, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5185, 25, 631, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5186, 26, 631, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5187, 27, 631, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5188, 18, 632, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5189, 19, 632, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5190, 20, 632, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5191, 21, 632, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5192, 22, 632, 3, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5193, 23, 632, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5194, 24, 632, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5195, 25, 632, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5196, 26, 632, 2, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5197, 27, 632, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5198, 18, 633, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5199, 19, 633, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5200, 20, 633, 2, 6, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5201, 21, 633, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5202, 22, 633, 2, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5203, 23, 633, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5204, 24, 633, 2, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5205, 25, 633, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5206, 26, 633, 0, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5207, 27, 633, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5208, 18, 634, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5209, 19, 634, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5210, 20, 634, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5211, 21, 634, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5212, 22, 634, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5213, 23, 634, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5214, 24, 634, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5215, 25, 634, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5216, 26, 634, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5217, 27, 634, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5218, 18, 635, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5219, 19, 635, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5220, 20, 635, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5221, 21, 635, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5222, 22, 635, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5223, 23, 635, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5224, 24, 635, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5225, 25, 635, 1, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5226, 26, 635, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5227, 27, 635, 5, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5228, 18, 636, 0, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5229, 19, 636, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5230, 20, 636, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5231, 21, 636, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5232, 22, 636, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5233, 23, 636, 0, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5234, 24, 636, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5235, 25, 636, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5236, 26, 636, 1, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5237, 27, 636, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5238, 18, 637, 2, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5239, 19, 637, 1, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5240, 20, 637, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5241, 21, 637, 1, 7, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5242, 22, 637, 1, 6, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5243, 23, 637, 2, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5244, 24, 637, 1, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5245, 25, 637, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5246, 26, 637, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5247, 27, 637, 2, 6, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5248, 18, 638, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5249, 19, 638, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5250, 20, 638, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5251, 21, 638, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5252, 22, 638, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5253, 23, 638, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5254, 24, 638, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5255, 25, 638, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5256, 26, 638, 4, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5257, 27, 638, 3, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5258, 18, 639, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5259, 19, 639, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5260, 20, 639, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5261, 21, 639, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5262, 22, 639, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5263, 23, 639, 0, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5264, 24, 639, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5265, 25, 639, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5266, 26, 639, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5267, 27, 639, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5268, 18, 640, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5269, 19, 640, 3, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5270, 20, 640, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5271, 21, 640, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5272, 22, 640, 2, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5273, 23, 640, 0, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5274, 24, 640, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5275, 25, 640, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5276, 26, 640, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5277, 27, 640, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5278, 18, 641, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5279, 19, 641, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5280, 20, 641, 4, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5281, 21, 641, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5282, 22, 641, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5283, 23, 641, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5284, 24, 641, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5285, 25, 641, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5286, 26, 641, 3, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5287, 27, 641, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5288, 18, 642, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5289, 19, 642, 6, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5290, 20, 642, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5291, 21, 642, 6, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5292, 22, 642, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5293, 23, 642, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5294, 24, 642, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5295, 25, 642, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5296, 26, 642, 5, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5297, 27, 642, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5298, 18, 643, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5299, 19, 643, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5300, 20, 643, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5301, 21, 643, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5302, 22, 643, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5303, 23, 643, 1, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5304, 24, 643, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5305, 25, 643, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5306, 26, 643, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5307, 27, 643, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5308, 18, 644, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5309, 19, 644, 8, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5310, 20, 644, 8, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5311, 21, 644, 8, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5312, 22, 644, 8, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5313, 23, 644, 7, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5314, 24, 644, 7, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5315, 25, 644, 7, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5316, 26, 644, 10, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5317, 27, 644, 8, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5318, 18, 645, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5319, 19, 645, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5320, 20, 645, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5321, 21, 645, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5322, 22, 645, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5323, 23, 645, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5324, 24, 645, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5325, 25, 645, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5326, 26, 645, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5327, 27, 645, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5328, 18, 646, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5329, 19, 646, 5, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5330, 20, 646, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5331, 21, 646, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5332, 22, 646, 6, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5333, 23, 646, 5, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5334, 24, 646, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5335, 25, 646, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5336, 26, 646, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5337, 27, 646, 7, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5338, 18, 647, 5, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5339, 19, 647, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5340, 20, 647, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5341, 21, 647, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5342, 22, 647, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5343, 23, 647, 5, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5344, 24, 647, 8, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5345, 25, 647, 6, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5346, 26, 647, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5347, 27, 647, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5348, 18, 648, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5349, 19, 648, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5350, 20, 648, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5351, 21, 648, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5352, 22, 648, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5353, 23, 648, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5354, 24, 648, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5355, 25, 648, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5356, 26, 648, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5357, 27, 648, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5358, 18, 649, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5359, 19, 649, 4, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5360, 20, 649, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5361, 21, 649, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5362, 22, 649, 5, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5363, 23, 649, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5364, 24, 649, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5365, 25, 649, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5366, 26, 649, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5367, 27, 649, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5368, 18, 650, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5369, 19, 650, 7, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5370, 20, 650, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5371, 21, 650, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5372, 22, 650, 6, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5373, 23, 650, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5374, 24, 650, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5375, 25, 650, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5376, 26, 650, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5377, 27, 650, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5378, 18, 651, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5379, 19, 651, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5380, 20, 651, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5381, 21, 651, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5382, 22, 651, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5383, 23, 651, 3, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5384, 24, 651, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5385, 25, 651, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5386, 26, 651, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5387, 27, 651, 3, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5388, 18, 652, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5389, 19, 652, 1, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5390, 20, 652, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5391, 21, 652, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5392, 22, 652, 5, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5393, 23, 652, 3, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5394, 24, 652, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5395, 25, 652, 5, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5396, 26, 652, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5397, 27, 652, 3, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5398, 18, 653, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5399, 19, 653, 3, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5400, 20, 653, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5401, 21, 653, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5402, 22, 653, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5403, 23, 653, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5404, 24, 653, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5405, 25, 653, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5406, 26, 653, 2, 0, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5407, 27, 653, 2, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5408, 18, 654, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5409, 19, 654, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5410, 20, 654, 3, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5411, 21, 654, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5412, 22, 654, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5413, 23, 654, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5414, 24, 654, 5, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5415, 25, 654, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5416, 26, 654, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5417, 27, 654, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5418, 18, 655, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5419, 19, 655, 4, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5420, 20, 655, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5421, 21, 655, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5422, 22, 655, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5423, 23, 655, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5424, 24, 655, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5425, 25, 655, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5426, 26, 655, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5427, 27, 655, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5428, 18, 656, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5429, 19, 656, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5430, 20, 656, 4, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5431, 21, 656, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5432, 22, 656, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5433, 23, 656, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5434, 24, 656, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5435, 25, 656, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5436, 26, 656, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5437, 27, 656, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5438, 18, 657, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5439, 19, 657, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5440, 20, 657, 2, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5441, 21, 657, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5442, 22, 657, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5443, 23, 657, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5444, 24, 657, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5445, 25, 657, 1, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5446, 26, 657, 2, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5447, 27, 657, 6, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5448, 18, 658, 2, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5449, 19, 658, 1, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5450, 20, 658, 2, 4, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5451, 21, 658, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5452, 22, 658, 4, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5453, 23, 658, 2, 3, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5454, 24, 658, 6, 5, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5455, 25, 658, 3, 2, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5456, 26, 658, 3, 1, '2026-04-02 22:20:10', NULL);
INSERT INTO public.prediction VALUES (5457, 27, 658, 3, 4, '2026-04-02 22:20:10', NULL);


--
-- Data for Name: rule_set; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.rule_set VALUES (11, 17, 1, 0.25, 2, '{"1":300,"2":150,"3":50}');


--
-- Data for Name: special_bet; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.special_bet VALUES (1046, 18, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1047, 18, 183, 28, NULL, NULL);
INSERT INTO public.special_bet VALUES (1048, 18, 184, 34, NULL, NULL);
INSERT INTO public.special_bet VALUES (1049, 19, 182, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1050, 19, 183, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1051, 19, 184, 28, NULL, NULL);
INSERT INTO public.special_bet VALUES (1052, 20, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1053, 20, 183, 28, NULL, NULL);
INSERT INTO public.special_bet VALUES (1054, 20, 184, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1055, 21, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1056, 21, 183, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1057, 21, 184, 34, NULL, NULL);
INSERT INTO public.special_bet VALUES (1058, 22, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1059, 22, 183, 28, NULL, NULL);
INSERT INTO public.special_bet VALUES (1060, 22, 184, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1061, 23, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1062, 23, 183, 34, NULL, NULL);
INSERT INTO public.special_bet VALUES (1063, 23, 184, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1064, 24, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1065, 24, 183, 28, NULL, NULL);
INSERT INTO public.special_bet VALUES (1066, 24, 184, 34, NULL, NULL);
INSERT INTO public.special_bet VALUES (1067, 25, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1068, 25, 183, 34, NULL, NULL);
INSERT INTO public.special_bet VALUES (1069, 25, 184, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1070, 26, 182, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1071, 26, 183, 34, NULL, NULL);
INSERT INTO public.special_bet VALUES (1072, 26, 184, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1073, 27, 182, 25, NULL, NULL);
INSERT INTO public.special_bet VALUES (1074, 27, 183, 32, NULL, NULL);
INSERT INTO public.special_bet VALUES (1075, 27, 184, 34, NULL, NULL);
INSERT INTO public.special_bet VALUES (1076, 18, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1077, 18, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1078, 18, 187, NULL, 'Hertl', NULL);
INSERT INTO public.special_bet VALUES (1079, 19, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1080, 19, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1081, 19, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1082, 20, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1083, 20, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1084, 20, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1085, 21, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1086, 21, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1087, 21, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1088, 22, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1089, 22, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1090, 22, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1091, 23, 185, NULL, 'Sedlák', NULL);
INSERT INTO public.special_bet VALUES (1092, 23, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1093, 23, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1094, 24, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1095, 24, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1096, 24, 187, NULL, 'Palát', NULL);
INSERT INTO public.special_bet VALUES (1097, 25, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1098, 25, 186, NULL, 'Kubalík', NULL);
INSERT INTO public.special_bet VALUES (1099, 25, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1100, 26, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1101, 26, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1102, 26, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1103, 27, 185, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (1104, 27, 186, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (1105, 27, 187, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (1106, 18, 188, NULL, NULL, 16);
INSERT INTO public.special_bet VALUES (1107, 19, 188, NULL, NULL, 26);
INSERT INTO public.special_bet VALUES (1108, 20, 188, NULL, NULL, 15);
INSERT INTO public.special_bet VALUES (1109, 21, 188, NULL, NULL, 20);
INSERT INTO public.special_bet VALUES (1110, 22, 188, NULL, NULL, 30);
INSERT INTO public.special_bet VALUES (1111, 23, 188, NULL, NULL, 15);
INSERT INTO public.special_bet VALUES (1112, 24, 188, NULL, NULL, 24);
INSERT INTO public.special_bet VALUES (1113, 25, 188, NULL, NULL, 14);
INSERT INTO public.special_bet VALUES (1114, 26, 188, NULL, NULL, 28);
INSERT INTO public.special_bet VALUES (1115, 27, 188, NULL, NULL, 21);
INSERT INTO public.special_bet VALUES (1116, 18, 189, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (1117, 19, 189, NULL, NULL, 11);
INSERT INTO public.special_bet VALUES (1118, 20, 189, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (1119, 21, 189, NULL, NULL, 3);
INSERT INTO public.special_bet VALUES (1120, 22, 189, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (1121, 23, 189, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (1122, 24, 189, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (1123, 25, 189, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (1124, 26, 189, NULL, NULL, 7);
INSERT INTO public.special_bet VALUES (1125, 27, 189, NULL, NULL, 0);
INSERT INTO public.special_bet VALUES (1126, 18, 190, NULL, NULL, 8);
INSERT INTO public.special_bet VALUES (1127, 19, 190, NULL, NULL, 24);
INSERT INTO public.special_bet VALUES (1128, 20, 190, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (1129, 21, 190, NULL, NULL, 12);
INSERT INTO public.special_bet VALUES (1130, 22, 190, NULL, NULL, 14);
INSERT INTO public.special_bet VALUES (1131, 23, 190, NULL, NULL, 4);
INSERT INTO public.special_bet VALUES (1132, 24, 190, NULL, NULL, 16);
INSERT INTO public.special_bet VALUES (1133, 25, 190, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (1134, 26, 190, NULL, NULL, 22);
INSERT INTO public.special_bet VALUES (1135, 27, 190, NULL, NULL, 6);


--
-- Data for Name: tournament_participant; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Name: match_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.match_id_seq', 658, true);


--
-- Name: point_entry_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.point_entry_id_seq', 12231, true);


--
-- Name: prediction_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.prediction_id_seq', 5457, true);


--
-- Name: rule_set_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.rule_set_id_seq', 11, true);


--
-- Name: special_bet_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.special_bet_id_seq', 1135, true);


--
-- Name: special_bet_rule_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.special_bet_rule_id_seq', 190, true);


--
-- Name: team_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.team_id_seq', 44, true);


--
-- Name: tournament_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tournament_id_seq', 17, true);


--
-- Name: tournament_participant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tournament_participant_id_seq', 89, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_id_seq', 27, true);


--
-- PostgreSQL database dump complete
--

\unrestrict pLKRAKRa0TaeHrypZL924ulBhz2EYF0yrUzG54gHhkZtQYASQ7fAek4CK7FifY0

