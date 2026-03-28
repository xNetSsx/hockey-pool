# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Hockey Pool — Symfony 7.1 web application using FrankenPHP (Caddy-based PHP runtime), PostgreSQL 16, Doctrine ORM, Twig + Tailwind CSS (via asset-mapper + symfonycasts/tailwind-bundle), and Stimulus. PHP >= 8.3.12 required.

## Common Commands

All commands run inside Docker containers via `make`:

```bash
make build              # Build Docker images
make up                 # Start containers (dev mode)
make down               # Stop containers
make bash               # Shell into PHP container
make cc                 # Clear Symfony cache
```

### Database

```bash
make migration          # Create a new migration
make run-migrations     # Execute pending migrations
make db-cleanup         # Drop and recreate database
make fixtures-load      # Load data fixtures
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
```

### Composer

```bash
make composer c="require package/name"   # Run arbitrary composer command
make composer-install                     # Install dependencies
```

## Architecture

- **Routing**: Attribute-based (`#[Route]`) on controllers in `src/Controller/`
- **Entities**: Doctrine attribute-mapped entities in `src/Entity/`
- **DI**: Autowiring + autoconfiguration enabled; services auto-registered from `src/`
- **Config**: `config/packages/` for bundle configs, `config/services.yaml` for DI
- **Templates**: Twig in `templates/`, base layout uses Tailwind CSS
- **Frontend**: Asset Mapper + Stimulus (no Node.js/webpack); Tailwind CSS v4 via `symfonycasts/tailwind-bundle`
- **Security**: `symfony/security-bundle` with in-memory provider (ready for User entity)
- **Docker**: Multi-stage Dockerfile (base → dev → prod); `compose.yaml` + `compose.override.yaml` (dev) + `compose.prod.yaml`

## Code Standards

- PHPStan at **max** level with Doctrine extension
- PHP CodeSniffer enforces PSR-12 + Symfony + Slevomat coding standards
- Strict type hints and return types required
- Forbidden annotations: `@author`, `@created`, `@version`, `@copyright`, `@license`, `@package`
