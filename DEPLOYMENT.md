# Deployment na Railway

## Předpoklady

- GitHub účet s repozitářem [xNetSsx/hockey-pool](https://github.com/xNetSsx/hockey-pool)
- Railway účet — [railway.app](https://railway.app) ($5 kredit na 30 dní zdarma, pak $1/měsíc)

## 1. Vytvoř nový projekt na Railway

1. Jdi na [railway.app](https://railway.app) → **New Project**
2. Vyber **Deploy from GitHub repo** → připoj GitHub → vyber `xNetSsx/hockey-pool`
3. Railway začne build — **zatím ho zruš**, nejdřív potřebuješ databázi

## 2. Přidej PostgreSQL databázi

1. V projektu klikni **+ New** → **Database** → **PostgreSQL**
2. Railway vytvoří databázi a automaticky nastaví `DATABASE_URL` jako shared variable
3. Klikni na PostgreSQL service → **Variables** → zkopíruj `DATABASE_URL` (bude potřeba ověřit)

## 3. Nastav proměnné prostředí

Klikni na svůj web service (ne databázi) → **Variables** → přidej:

| Variable | Value |
|----------|-------|
| `APP_ENV` | `prod` |
| `APP_SECRET` | *(spusť `openssl rand -hex 16` v terminálu)* |
| `DATABASE_URL` | *(reference na PostgreSQL — Railway to nastaví automaticky pokud propojíš služby)* |
| `TRUSTED_PROXIES` | `REMOTE_ADDR` |

## 4. Nastav Dockerfile

V service settings → **Build**:
- **Builder**: Dockerfile
- **Dockerfile path**: `Dockerfile.railway`

Railway automaticky nastaví `PORT` env var — Dockerfile to čte.

## 5. Deploy

Klikni **Deploy** nebo pushni do `main` branche — Railway automaticky nasadí.
Build trvá cca 3-5 minut.

## 6. Po prvním deployi — migrace a data

V Railway dashboardu → tvůj web service → záložka **Shell**:

```bash
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
php bin/console app:recalculate-points oh-2026
```

## 7. Hotovo!

Railway ti dá URL typu `https://hockey-pool-production-xxxx.up.railway.app`.

Přihlas se:
- Uživatel: `Ondra` (admin), heslo: `heslo123`
- Nebo kdokoliv z 10 hráčů

## Vlastní doména (volitelné)

V Railway → tvůj service → **Settings** → **Networking** → **Custom Domain**.
Přidej svou doménu a nastav DNS CNAME.

## Aktualizace

Každý push do `main` automaticky spustí nový deploy.
Po změně DB schématu spusť migrace přes Shell.

## Cena

- Trial: 30 dní, $5 kredit zdarma
- Poté: $1/měsíc + usage (pro 10 hráčů kontrolujících skóre pár krát denně to bude minimální)
- PostgreSQL je v ceně
