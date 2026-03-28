# Deployment na Koyeb (free tier)

## Předpoklady

- GitHub účet s tímto repozitářem
- Koyeb účet (zdarma, bez kreditní karty) — [app.koyeb.com](https://app.koyeb.com)

## 1. Vytvoř PostgreSQL databázi

1. V Koyeb dashboardu jdi na **Databases** → **Create Database**
2. Vyber region **Frankfurt** (`fra`)
3. Název: `hockey-pool` (nebo libovolný)
4. Koyeb vytvoří databázi a ukáže connection string — zkopíruj ho

> Free tier: DB se uspí po 5 minutách neaktivity. První request po probuzení trvá 2-3 sekundy.

## 2. Vytvoř Web Service

1. V Koyeb dashboardu jdi na **Apps** → **Create App** → **Web Service**
2. **Source**: GitHub → vyber tento repozitář
3. **Builder**: Dockerfile
4. **Dockerfile path**: `Dockerfile.koyeb`
5. **Region**: Frankfurt (`fra`)
6. **Instance type**: Free (512 MB RAM)
7. **Port**: `8080`

## 3. Nastav Environment Variables

V záložce **Environment variables** přidej:

| Variable | Value |
|----------|-------|
| `APP_ENV` | `prod` |
| `APP_SECRET` | *(vygeneruj: `openssl rand -hex 16`)* |
| `DATABASE_URL` | *(connection string z kroku 1)* |
| `TRUSTED_PROXIES` | `REMOTE_ADDR` |

> `DATABASE_URL` formát: `postgresql://user:password@host:port/dbname?sslmode=require`

## 4. Deploy

1. Klikni **Deploy** — Koyeb sestaví Docker image a spustí ho
2. Build trvá cca 3-5 minut (stahuje PHP extensions, Composer dependencies, Tailwind binary)
3. Po úspěšném deployi dostaneš URL typu `https://hockey-pool-xxx.koyeb.app`

## 5. Spusť migrace a fixtures

Po prvním deployi je potřeba vytvořit databázové tabulky a naplnit data.

V Koyeb dashboardu jdi na svou službu → **Console** (SSH) a spusť:

```bash
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
php bin/console app:recalculate-points oh-2026
```

## 6. Hotovo!

Přihlas se na `https://tvoje-url.koyeb.app/login`:
- Uživatel: `Ondra` (admin), heslo: `heslo123`
- Nebo kdokoliv z 10 hráčů

## Aktualizace

Při pushnutí do `main` branche Koyeb automaticky znovu nasadí.
Migrace je potřeba spustit ručně přes Console po deploy pokud se změnilo DB schéma.

## Troubleshooting

**App nereaguje po deploy:**
- Zkontroluj logy v Koyeb dashboardu → **Logs**
- Ověř že `DATABASE_URL` je správně nastavená

**Pomalý první request:**
- Normální — free tier PostgreSQL se uspí po 5 min neaktivity
- Probuzení trvá 2-3 sekundy

**500 Internal Server Error:**
- Zkontroluj `APP_SECRET` — nesmí být prázdný
- Zkontroluj `APP_ENV=prod`
- Spusť `php bin/console cache:clear --env=prod` přes Console
