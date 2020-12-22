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
make build-migration | creates migration based on entity file updates
make clear-cache    | clears symfony cache, run this after making changes to configuration files
make build-cache    | clears and rebuilds symfony cache, may take some time
make .env           | creates .env from .env-dist if not present
make fix-cs         | applies code formating standards to all .php files in `src` and `test` folders
make test           | runs all unit and feature tests
make test-unit      | runs all unit tests
make test-feature   | runs all feature tests
make start-consumers | starts 5 worker processes for consuming queue messages
make stop-consumers | stops consumer worker processes

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
Swagger Documentation: http://localhost:8080  
Api Platform: https://api-platform.com  
Symfony: https://symfony.com/doc/current/index.html   
Doctrine ORM: https://symfony.com/doc/current/doctrine.html  
Composer: https://getcomposer.org/  
RabbitMQ: https://www.rabbitmq.com/documentation.html    

## Brokerage Api documentation

TD Ameritrade Api: https://developer.tdameritrade.com/apis  
Alpaca Trade Api: https://alpaca.markets/docs/api-documentation/api-v2/


Commands:

Command                             |   Description
------------------------------------|------------------------ 
`stocks-api:api:sync-ticker-types`  | syncs db ticker types with Polygon.IO api
`stocks-api:api:sync-tickers`       | syncs db tickers with Polygon.IO api.
`stocks-api:api:sync-orders`        | Work in progress for command, but can run via `/api/stocks/v1/account/{UUID}/sync_orders` endpoint

## PhpCS Fixer
Run `make fix-cs` in the terminal. This will inspect and fix all php files in the src and test directories. It will 
also validate syntax. 

## Database  
Mysql 8.0  
Access database commandline via running `dev-mysql` in the terminal (not inside docker container). Use database `stocks_api`. For consistency Docrine ORM is used. Entities are created in the 
`src/Entity` directory. This includes the Doctrine `@ORM` annotations to create schema. Changes to the entity will 
cause database schema validation to fail. Run `make build-migration` to create a new migration with changes add a 
description in the created file in the `src/Migrations` directory. Then run `make build-migrate` to execute the 
migration. 

## MessageClient Integration (RabbitMQ)
Admin Url: localhost:15672
User: guest  
Pass: guest  

Setup supervisord for threading, may have to tweak as message volume increases. 

Run `make start-consumers` to register all listeners and queues
This will register 5 workers to handle all message consumption

Currently used for the following:

Commands:
`bin/console stocks-api:api:sync-tickers`  You can use the `-t or --type` parameter to specify a ticker type 
ie. 'common stock'. Must be a valid ticker type which can be obtained from the
`/api/stocks/v1/ticker_types` endpoint. Will throw an error on invalid type. It's a bit slow,
but it will refresh all tickers daily eventually. It publishes fetched tickers to the queue and processes
them asynchronously. You can view progress via the RabbitMQ admin interface above.

The MessageClient folder in the src directory will be moved to a bundle in the next major push.

## Satis
Admin Url: http://localhost:8081  
Setup a minimal satis server. Still confguring it to pull down updated bundles from github. Eventually you can 
clone a bundle repo in the `repos` directory and run `composer install` and it will symlink the bundle in the vendor 
folder for development. Alternatively you would add the desired bundle to the `composer.json` file to install directly
as package for production use. 