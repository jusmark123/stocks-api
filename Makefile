testEnvVars := APP_ENV=test \
JWT_PRIVATE_KEY_PATH=tests/_fixtures/jwt/private.pem \
JWT_PUBLIC_KEY_PATH=tests/_fixtures/jwt/public.pem \
JWT_PASSPHRASE=$(shell cat tests/_fixtures/jwt/passphrase.txt)
jwt-filepath=/opt/app-root/src/config/jwt/
userId := $(shell id -u)
groupId := $(shell id -g)

.env:
	cp .env.dist .env

symfony-cli:
	XDEBUG_MODE=off curl -sS https://get.symfony.com/cli/installer | bash
	mv /root/.symfony/bin/symfony /usr/local/bin/symfony
.PHONY: symfony-cli

local: .env
	aws ecr get-login-password --region us-west-2 | docker login --username AWS --password-stdin 448507992616.dkr.ecr.us-west-2.amazonaws.com
	docker-compose up -d
	dev-exec make build
.PHONY:local

build-cache:
	rm -rf var/cache/*
	bin/console cache:warmup --no-debug --no-interaction
PHONY: build-cache

build-database: build-drop-database build-create-database build-migrate build-validate-database build-fixtures
.PHONY: build-database

build: .env generate-keys build-dependencies build-database
.PHONY: build

build-dependencies:
	XDEBUG_MODE=off  php -d memory_limit=-1 `which composer` install --no-interaction --no-ansi --optimize-autoloader
.PHONY: build-dependencies

build-drop-database:
	bin/console doctrine:database:drop --if-exists --force
.PHONY: build-drop-database

build-create-database:
	bin/console doctrine:database:create --if-not-exists --no-interaction
.PHONY: build-create-database

build-fixtures:
	bin/console doctrine:fixture:load --no-interaction
.PHONY: build-fixtures

build-migrate:
	bin/console doctrine:migrations:migrate --no-interaction
.PHONY: build-migrate

build-migration:
	bin/console doctrine:cache:clear-metadata
	bin/console doctrine:migration:diff --formatted
.PHONY: migration

build-validate-database:
	bin/console doctrine:schema:validate
.PHONY: build-validate-database

clear-cache:
	rm -rf var/cache/*
	rm -rf /tmp/behat*
	rm -rf build/
.PHONY: clear-cache

clear-dev-log:
	rm -rf var/log/dev.log
.PHONY: clear-dev-log

clear-xdebug-log:
	rm -rf var/log/xdebug.log
	touch var/log/xdebug.log
.PHONY: clear-xdebug-log

clear-logs:
	rm -rf logs/symfony/*
	rm -rf var/log/*
.PHONY: clear-logs

clean: clean-cs clear-cache
	rm -f .env
	rm -rf vendor/
	rm -f composer.lock
	rm -f symfony.lock
.PHONY: clean

clean-composer:
	rm -rf vendor/
	rm -rf ~/.composer/cache
	rm -rf composer.lock symfony.lock
.PHONY: clean-composer

clean-cs:
	rm -f .php_cs.cache
.PHONY: clean-cs

fix-cs:
	rm -f .php_cs.cache
	XDEBUG_MODE=off php-cs-fixer fix tests --show-progress=estimating --verbose --config=.php_cs.dist --allow-risky=yes
	XDEBUG_MODE=off php-cs-fixer fix src --show-progress=estimating --verbose --config=.php_cs.dist --allow-risky=yes
.PHONY: fix-cs

generate-keys:
	mkdir -p $(jwt-filepath)
	php -r 'echo base64_encode(random_bytes(32)), PHP_EOLl;' > $(jwt-filepath)passphrase.txt
	openssl genrsa -passout pass:$(cat $(jwt-filepath)passphrase.txt) -out $(jwt-filepath)private.pem 2048
	openssl rsa -in $(jwt-filepath)private.pem -passin pass:$(cat $(jwt-filepath)passphrase.txt) -pubout -out $(jwt-filepath)public.pem
	chmod 600 $(jwt-filepath)passphrase.txt
	chmod 600 $(jwt-filepath)private.pem
	chmod 600 $(jwt-filepath)public.pem
.PHONY: generate-keys

show-log:
	tail -f var/log/dev.log
.PHONY: show-log

show-xdebug-log:
	tail -f logs/symfony/xdebug.log
.PHONY: show-xdebug-log

supervisor:
	supervisord -c docker/php-fpm/supervisord.conf
	supervisorctl -c docker/php-fpm/supervisord.conf
.PHONY: supervisor

test: static  test-unit test-feature
.PHONY: test

test-feature:
	$(testEnvVars) \
	. bin/behat_wrapper.sh
#	php -d memory_limit=-1 vendor/bin/behat --format progress -vv --stop-on-failure
.PHONY: test-feature

test-health:
	curl localhost:8080/health
.PHONY: test-health

test-unit:
	vendor/bin/simple-phpunit \
		--coverage-html build/coverage \
		--coverage-clover build/coverage/clover.xml \
		--log-junit build/reports/phpunit/junit.xml
.PHONY: unit-test

static:
	php -d memory_limit=-1 vendor/bin/phpstan analyse --level 1 src public
.PHONY: static

start-consumers:
	supervisorctl --configuration /etc/supervisor/conf.d/supervisord.conf start all
.PHONY: start-consumers

stop-consumers:
	supervisorctl --configuration /etc/supervisor/conf.d/supervisord.conf stop all
.PHONY: stop-consumers

sync-tickers:
	bin/console stocks-api:api:sync-tickers
.PHONY: sync-tickers