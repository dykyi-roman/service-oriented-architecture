placeholder:
	@echo "----------------------------------------------------------"
	@echo "| COMMAND            | DESCRIPTION                       |"
	@echo "----------------------------------------------------------"
	@echo "| init               | Up from the ground                |"
	@echo "| start              | Up all docker containers          |"
	@echo "| stop               | Down all docker containers        |"
	@echo "| restart            | Restart all docker containers     |"
	@echo "| ------------------ | --------------------------------- |"
	@echo "| docker-sync-start  | Up docker sync                    |"
	@echo "| docker-sync-stop   | Down docker sync                  |"
	@echo "| docker-sync-clean  | Clean docker sync                 |"
	@echo "| ------------------ | --------------------------------- |"
	@echo "| cache-clear        | Clear cache                       |"
	@echo "| phpcs              | Run phpcs                         |"
	@echo "| psalm              | Run psalm                         |"
	@echo "| php-test           | Run phpunit tests                 |"
	@echo "| postman-test       | Run postman tests                 |"
	@echo "| pre-commit         | phpcs clean-code psalm tests-run  |"

init:
	docker-compose down -v --remove-orphans
	docker-compose pull
	docker-compose build
	docker-compose up -d

start:
	docker-compose up -d

stop:
	docker-compose down

restart: stop start

cache-clear:
	rm -rf code/storage/framework/cache/*
	@echo "Cache is clean!"

phpcs:
	docker-compose exec php sh -c "vendor/bin/phpcs --standard=PSR2 src/"
	@echo "phpcs done"

psalm:
	docker-compose exec php sh -c "vendor/bin/psalm"
	@echo "psalm done"

php-tests:
	docker-compose exec php sh -c "vendor/bin/phpunit tests/"

postman-test:
	docker-compose run postman_checks

pre-commit: phpcs psalm php-tests postman-test
	@:

docker-sync-start:
	docker-sync-stack start

docker-sync-stop:
	docker-compose down

docker-sync-clean:
	docker-sync-stack clean