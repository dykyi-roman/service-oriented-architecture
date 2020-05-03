placeholder:
	@echo "----------------------------------------------------"
	@echo "| COMMAND      | DESCRIPTION                       |"
	@echo "----------------------------------------------------"
	@echo "| init         | Up from the ground                |"
	@echo "| start        | Up all docker containers          |"
	@echo "| stop         | Down all docker containers        |"
	@echo "| restart      | Restart all docker containers     |"
	@echo "| cache-clear  | Clear cache                       |"
	@echo "| phpcs        | Run phpcs                         |"
	@echo "| clean-code   | Run easy-coding-standard          |"
	@echo "| psalm        | Run psalm                         |"
	@echo "| php-test     | Run phpunit tests                 |"
	@echo "| postman-test | Run postman tests                 |"
	@echo "| pre-commit   | phpcs clean-code psalm tests-run  |"

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

clean-code:
	docker-compose exec php sh -c "vendor/bin/ecs check ./src/ --set clean-code"
	@echo "clean-code done"

psalm:
	docker-compose exec php sh -c "vendor/bin/psalm"
	@echo "psalm done"

php-tests:
	docker-compose exec php sh -c "vendor/bin/phpunit tests/"

postman-test:
	docker-compose run postman_checks

pre-commit: phpcs clean-code psalm php-tests postman-test
	@: