.env:
	cp .env.dist .env

local: .env
	docker-compose up -d
	dev-exec make build
.PHONY:local

build-cache:
	rm -rf var/cache/*
	bin/console cache:warmup --no-debug --no-interaction
PHONY: build-cache

build-database: build-drop-database build-create-database build-migrate build-validate-database build-fixtures
.PHONY: build-database

build: .env build-dependencies build-database
.PHONY: build

build-dependencies:
	php -d memory_limit=-1 `which composer` install --no-interaction --no-ansi --optimize-autoloader
.PHONY: build-dependencies

build-drop-database:
	bin/console doctrine:database:drop --force --if-exists
.PHONY: build-drop-database

build-create-database:
	bin/console doctrine:database:create --if-not-exists --no-interaction
.PHONY: build-create-database

build-fixtures:
	bin/console doctrine:fixture:load --no-interaction --append
.PHONY: build-fixtures

build-migrate:
	bin/console doctrine:migrations:migrate --no-interaction
.PHONY: build-migrate

build-validate-database:
	bin/console doctrine:schema:validate
.PHONY: build-validate-database

migration:
	bin/console doctrine:migration:diff --formatted
.PHONY: migration

clear-cache:
	rm -rf var/cache/*
	rm -rf /tmp/behat*
	rm -rf build/
.PHONY: clear-cache

clean: clean-cs clear-cache
	rm -f .env
	rm -rf vendor/
	rm -f composer.lock
	rm -f symfony.lock
.PHONY: clean

clean-cs:
	rm -f .php_cs.cache
.PHONY: clean-cs

fix-cs:
	rm -f .php_cs.cache
	php-cs-fixer fix tests --show-progress=estimating --verbose --config=.php_cs.dist --allow-risky=yes
	php-cs-fixer fix src --show-progress=estimating --verbose --config=.php_cs.dist --allow-risky=yes
.PHONY: fix-cs

show-log:
	tail -f var/log/dev.log
.PHONY: show-log

unit-test:
	./vendor/bin/simple-phpunit --coverage-html build/coverage --coverage-clover build/coverage/clover.xml
.PHONY: unit-test

##### Satis Commands ####

.PHONY: satis-init satis-up satis-start satis-stop satis-restart satis-state satis-remove satis-bash satis-build satis-down satis-logs

###############################################
##     VARIABLES                             ##
###############################################
compose=docker-compose
image=ypereirareis/docker-satis

###############################################
##      TARGETS                              ##
###############################################
satis-up:
	@echo "== START =="
	$(compose) up -d stocks-api-satis

satis-start: satis-up

satis-init:
	@$(compose) build --pull

satis-rebuild:
	@$(compose) build --pull --no-cache stocks-api-satis

satis-stop:
	@echo "== STOP =="
	@$(compose) stop

satis-restart: satis-start

satis-state:
	@echo "== STATE =="
	@$(compose) ps

satis-remove:
	@echo "== REMOVE =="
	@$(compose) rm --force

satis-bash:
	@echo "== BASH =="
	@$(compose) exec stocks-api-satis bash

satis-logs:
	@$(compose) logs -ft --tail=1000

satis-down:
	@$(compose) down --volumes --remove-orphans stocks-api-satis

satis-build:
	@echo "== SATIS BUILD =="
	@$(compose) exec stocks-api-satis ./scripts/build.sh
