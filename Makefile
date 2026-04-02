# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
VENDOR = $(PHP) vendor/bin/
DOCKER_EXEC_TEST = $(DOCKER_COMP) exec -e APP_ENV=test php

# COLORS
RED  := $(shell tput -Txterm setaf 1)
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
BLUE   := $(shell tput -Txterm setaf 4)

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up down composer vendor sf cc test

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: _build
_build:
	@echo "${GREEN}>>> building container${EOL}"
	@$(DOCKER_COMP) build --pull --no-cache

up: _start_docker
_start_docker:
	@$(DOCKER_COMP) up -d

stop: down
down: ## Stop the docker hub
	@$(DOCKER_COMP) down -t 30

restart: down up

bash: ## Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

_create-env:
	@test -f .env.local || touch .env.local

_create-local-makefile:
	@test -f makefileLocal.mk || (touch makefileLocal.mk ; echo '-include .env.local' > makefileLocal.mk)

## Clear cache
cc: cache-clear
cache-clear:
	@echo "${GREEN}>>> Clear cache${EOL}"
	@$(PHP) -d memory_limit=256M bin/console cache:clear

clear-doctrine-cache:
	@echo "${YELLOW}>>>clearing doctrine metadata, result and query${EOL}"
	@$(SYMFONY) doctrine:cache:clear-metadata
	@$(SYMFONY) doctrine:cache:clear-result
	@$(SYMFONY) doctrine:cache:clear-query

### MIGRATIONS ###
## Create db diff for migration purposes
diff:
	@echo "${GREEN}>>> Dumping db diff${EOL}"
	@$(PHP_CONT) bin/console doctrine:schema:update --dump-sql --complete

## Create migration
migration:
	@echo "${GREEN}>>> Creating migration${EOL}"
	@$(PHP_CONT) bin/console doctrine:migrations:diff
	@git add migrations/$(shell /bin/date +%Y)/$(shell /bin/date +%m)/*

## Run migrations
run-migrations:
	@echo "${GREEN}>>> Running migrations${EOL}"
	@$(PHP_CONT) bin/console doctrine:migrations:migrate --no-interaction

## Migration status
migration-status:
	@echo "${GREEN}>>> Provides migration status${EOL}"
	@$(PHP_CONT) bin/console doctrine:migrations:status

## Cleanup merged local branches
git-cleanup:
	@echo "${GREEN}>>> Cleanup merged local branches${EOL}"
	@git branch --merged origin/master | grep -v '*' | xargs -n 1 git branch -d

## Drop DB, run migrations, apply seed.sql and seed MS2026 data
seed-apply: db-cleanup run-migrations
	@echo "${GREEN}>>> applying seed.sql${EOL}"
	@$(DOCKER_COMP) exec -T database psql -U db_user db_name --single-transaction -v ON_ERROR_STOP=1 < docker/seed.sql
	@echo "${GREEN}>>> seeding MS2026${EOL}"
	@$(SYMFONY) app:seed-ms2026

## Cleanup database
db-cleanup:
	@echo "${YELLOW}>>>Cleanup database${EOL}"
	@$(DOCKER_COMP) exec database psql -U db_user postgres -c "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = 'db_name' AND pid <> pg_backend_pid();" > /dev/null
	@$(PHP_CONT) bin/console doctrine:database:drop --force --if-exists --no-interaction
	@$(PHP_CONT) bin/console doctrine:database:create --if-not-exists --no-interaction

# COMPOSER
## Run composer install inside container
composer: composer-install
composer-install:
	@echo "${GREEN}>>> Run composer install${EOL}"
	@$(COMPOSER) install

## Run composer update inside container
composer-update:
	@echo "${GREEN}>>> Run composer update${EOL}"
	@$(COMPOSER) update

## Re-create composer autoload
composer-autoload:
	@echo "${GREEN}>>> Re-create composer autoload${EOL}"
	@$(COMPOSER) dump-autoload

composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

###Fixtures
foundry: fixture-factory
foundry-factory: fixture-factory
fixture-factory:
	@echo "${GREEN}>>> create fixture factory${EOL}"
	@$(PHP_CONT) bin/console make:factory --namespace="App\DataFixtures\Factory"
	@git add src/DataFixtures/Factory

fixtures-load:
	@echo "${GREEN}>>> load fixtures${EOL}"
	@$(PHP_CONT) bin/console doctrine:fixtures:load --no-interaction

## Load fixtures and dump DB to docker/seed.sql for production deploys
seed-dump: fixtures-load
	@echo "${GREEN}>>> dumping database to docker/seed.sql${EOL}"
	@$(DOCKER_COMP) exec database pg_dump -U db_user --data-only --inserts --no-owner --no-privileges --exclude-table=doctrine_migration_versions db_name > docker/seed.sql
	@echo "${GREEN}>>> done: docker/seed.sql${EOL}"

## Recalculate points for all tournaments
recalculate:
	@echo "${GREEN}>>> recalculating points for all tournaments${EOL}"
	@$(PHP_CONT) bin/console app:recalculate-points --all --no-debug

### TESTS
### Unit
paratest: unit-tests e2e

unit: unit-tests
paratest-unit: unit
unit-tests:
	@echo "${GREEN}>>> Running non e2e tests${EOL}"
	@$(DOCKER_EXEC_TEST) php ./vendor/bin/paratest --processes=8 -c phpunit.xml.dist

e2e: assets-prod-compile paratest-e2e assets-prod-compile-rm
panther: e2e
paratest-e2e:
	@echo "${GREEN}>>> Running e2e tests${EOL}"
	@$(DOCKER_EXEC_TEST) ./vendor/bin/paratest --processes=8 -c phpunit-e2e.xml.dist

test1:
	@echo "${GREEN}>>> Run one test (i.e. make test1 f=testCalculateTotalCost)${EOL}"
	@$(DOCKER_EXEC_TEST) ./vendor/bin/phpunit --filter=$(f)

test-file:
	@echo "${GREEN}>>> Run one test file (i.e. make test-file f=tests/Unit/Api/InternetMarketing/Zbozi/ApiZboziResolverTest.php)${EOL}"
	@$(DOCKER_EXEC_TEST) ./vendor/bin/phpunit $(f)

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit $(c)

### PHPStan
phpstan:
	@echo "${GREEN}>>> Running PHPStan${EOL}"
	@$(PHP_CONT) ./vendor/bin/phpstan analyse --memory-limit=1G -c config/phpstan.neon


### PHP_CodeSniffer
phpcs:
	@echo "${GREEN}>>> Running PHP CodeSniffer${EOL}"
	@$(PHP_CONT) ./vendor/bin/phpcs -d memory_limit=256M src/ tests/ -p

### Rector
rector:
	@echo "${GREEN}>>> Running Rector${EOL}"
	@$(PHP_CONT) ./vendor/bin/rector process

