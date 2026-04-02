# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Hockey Pool — Symfony 7.4 web application using Apache (php:8.3-apache), PostgreSQL 16, Doctrine ORM, Twig + Tailwind CSS (via asset-mapper + symfonycasts/tailwind-bundle), and Stimulus. PHP >= 8.3.12 required.

## Common Commands

All commands run inside Docker containers via `make`:

```bash
make build              # Build Docker images
make up                 # Start containers (dev mode)
make down               # Stop containers
make restart            # Restart containers
make bash               # Shell into PHP container
make cc                 # Clear Symfony cache
```

### Database

```bash
make migration          # Create a new migration
make run-migrations     # Execute pending migrations
make migration-status   # Check migration status
make db-cleanup         # Drop and recreate database
make diff               # Dump schema SQL diff (no migration created)
make clear-doctrine-cache  # Clear Doctrine metadata/result/query caches
make fixtures-load      # Load data fixtures
make fixture-factory    # Create a new Foundry fixture factory
make seed-dump          # Load fixtures and dump DB to docker/seed.sql
```

### Testing

```bash
make unit               # Run unit tests (paratest, 8 processes)
make e2e                # Run E2E tests (Panther/Chromium)
make test1 f=testName   # Run a single test by function name
make test-file f=path   # Run a specific test file
```

### Code Quality

```bash
make phpstan            # Static analysis (level max)
make phpcs              # Code style check (PSR-12 + Symfony + Slevomat standards)
make rector             # Automated code modernisation (rector/rector)
```

### Composer

```bash
make composer c="require package/name"   # Run arbitrary composer command
make composer-install                     # Install dependencies
make composer-update                      # Update dependencies
```

### Other

```bash
make recalculate        # Run app:recalculate-points --all
make sf c="..."         # Run arbitrary Symfony console command
```

## Architecture

- **Routing**: Attribute-based (`#[Route]`) on controllers in `src/Controller/`
- **Entities**: Doctrine attribute-mapped entities in `src/Entity/`
- **DI**: Autowiring + autoconfiguration enabled; services auto-registered from `src/`
- **Config**: `config/packages/` for bundle configs, `config/services.yaml` for DI
- **Templates**: Twig in `templates/`, base layout uses Tailwind CSS
- **Frontend**: Asset Mapper + Stimulus (no Node.js/webpack); Tailwind CSS v4 via `symfonycasts/tailwind-bundle`
- **Security**: `symfony/security-bundle` with `User` entity, form login, `ROLE_USER`/`ROLE_ADMIN` hierarchy, `PredictionVoter`, `LoginListener`
- **Service layer**: `src/Service/Manager/` (entity ops), `src/Service/Resolver/` (point/bet calculation), `src/Service/Builder/` (view-model aggregation), `src/Service/Provider/` (read-only access)
- **Other src/**: `src/Enum/` (TournamentStatus, TournamentPhase, BetValueType, BetScoringType), `src/Command/` (console commands), `src/Twig/` (extensions), `src/EventListener/`, `src/Security/Voter/`
- **Docker**: `Dockerfile` (Apache); `compose.yaml` + `compose.override.yaml` (dev) + `compose.prod.yaml` (prod)

## Code Standards

- PHPStan at **max** level with Doctrine extension
- PHP CodeSniffer enforces PSR-12 + Symfony + Slevomat coding standards
- Strict type hints and return types required
- Forbidden annotations: `@author`, `@created`, `@version`, `@copyright`, `@license`, `@package`
