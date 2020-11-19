# Stocks Api

## Local Dev

### Setup

1. Download the [local-dev](https://github.com/jusmark123/local-dev) git repo and follow setup instructions
2. Navigate to `Public` folder and git pull this project and enter stocks_api directory.
3. Create a folder name `repos` in the base directory
4. Open terminal and run `make local` command which does the following actions:

  - starts docker containers
  - installs dependencies
  - builds database
  - creates and loads data fixtures

5. Create a new git branch and begin coding

### Important Commands

#### Bash commands

Command            | Description
------------------ | --------------------------------------------------
dev-exec `command` | Enters container and will run command if specified
dev-mysql          | Enters mysql container

#### Make Commands

Command             | Description
------------------- | ----------------------------------------------------------------------------------------------------------
make local          | runs environment setup for development
make build          | installs dependencies, creates database, runs all migrations, validates database, and builds data fixtures
make build-database | creates database and runs all migrations
make build-migrate  | runs all migrations
make clear-cache    | clears symfony cache, run this after making changes to configuration files
make build-cache    | clears and rebuilds symfony cache, may take some time
make .env           | creates .env from .env-dist if not present
make fix-cs         | applies code formating standards to all .php files in `src` and `test` folders
make test           | runs all unit and feature tests
make test-unit      | runs all unit tests

***See make file for additional commands**

#### Symfony Commands

All Symfony commands are prefixed by `bin/console`. Run `bin/console` to list all available commands

Command                         | Description
------------------------------- | ------------------------------------------------------------------------------------
cache:clear                     | Clears the cache
debug:autowiring                | Lists classes/interfaces you can use for autowiring
debug:router                    | Displays current routes for an application
doctrine:migrations:diff        | Generate a migration by comparing your current database to your mapping information.
doctrine:migrations:dump-schema | Dump the schema for your database to a migration.
doctrine:migrations:execute     | Execute one or more migration versions up or down manually.
doctrine:migrations:migrate     | Execute a migration to a specified version or the latest available version.
doctrine:schema:validate        | Validate the mapping files

## Links

[Api Platform](https://api-platform.com)<br>
[Symfony](https://symfony.com/doc/current/index.html)<br>
[Doctrine ORM](https://symfony.com/doc/current/doctrine.html)<br>
[Composer](https://getcomposer.org/)<br>
[RabbitMQ](https://www.rabbitmq.com/documentation.html)

## Brokerage Api documentation

[TD Ameritrade Api](https://developer.tdameritrade.com/apis)<br>
[Alpaca Trade Api](https://alpaca.markets/docs/api-documentation/api-v2/)
