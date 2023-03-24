# PHP symfony environment with JSON REST API
Docker environment (based on official php and mysql docker hub repositories) required to run Symfony with JSON REST API.

## Requirements
* Docker version 18.06 or later
* Docker compose version 1.22 or later
* An editor or IDE
* MySQL Workbench

Note: OS recommendation - Linux Ubuntu based.

## Components
1. Nginx 1.23
2. PHP 8.2 fpm
3. MySQL 8
4. Symfony 6
5. RabbitMQ 3
6. Redis 7


1.Build, start and install the docker images from your terminal:
```bash
make build
make start
make composer-install
make generate-jwt-keys
```

2.Make sure that you have installed migrations / created roles and groups / messenger transports / elastic template:
```bash
make migrate
make create-roles-groups
make migrate-cron-jobs
make messenger-setup-transports
make elastic-create-or-update-template
```

3.In order to use this application, please open in your browser next urls:
- [http://localhost/api/doc](http://localhost/api/doc)
- [http://localhost:5601 (Kibana)](http://localhost:5601)

## Start and stop environment
Please use next make commands in order to start and stop environment:
```bash
make start
make stop
```
Note 1: For prod environment need to be used next make commands: `make start-prod`, `make stop-prod`.

## Additional main command available
```bash
make build
make build-test
make build-staging
make build-prod

make start
make start-test
make start-staging
make start-prod

make stop
make stop-test
make stop-staging
make stop-prod

make restart
make restart-test
make restart-staging
make restart-prod

make env-staging
make env-prod

make generate-jwt-keys

make ssh
make ssh-root
make ssh-nginx
make ssh-supervisord
make ssh-mysql
make ssh-rabbitmq

make composer-install-no-dev
make composer-install
make composer-update

make info

make logs
make logs-nginx
make logs-supervisord
make logs-mysql
make logs-rabbitmq

make drop-migrate
make migrate
make migrate-no-test
make migrate-cron-jobs

make fixtures

make create-roles-groups


make phpunit
make report-code-coverage

etc....
```
Notes: Please see more commands in Makefile

