placeholder:
	@echo "----------------------------------------------------"
	@echo "| COMMAND      | DESCRIPTION                       |"
	@echo "----------------------------------------------------"
	@echo "| start        | Up all docker containers          |"
	@echo "| stop         | Down all docker containers        |"
	@echo "| supervisor   | supervisor [COMMAND NAME]         |"
	@echo "| clear-cache  | Clear cache                       |"
	@echo "| phpcs        | Run phpcs                         |"
	@echo "| clean-code   | Run easy code standard            |"
	@echo "| psalm        | Run psalm                         |"
	@echo "| tests-run    | Run phpunit tests                 |"
	@echo "| postman-test | Run postman tests                 |"
	@echo "| pre-commit   | phpcs clean-code psalm tests-run  |"

start:
	@docker-compose up -d

stop:
	@docker-compose down

supervisor:
	@docker-compose exec php sh -c "supervisorctl $*"

clear-cache:
	@rm -rf code/var/cache/*
	@echo "Cache is clean!"

phpcs:
	@docker-compose exec php sh -c "vendor/bin/phpcs --standard=PSR2 src/"
	@echo "phpcs done!"

clean-code:
	@docker-compose exec php sh -c "vendor/bin/ecs check ./src/ --set clean-code"
	@echo "phpcs done!"

psalm:
	@docker-compose exec php sh -c "vendor/bin/psalm"
	@echo "psalm done!"

tests-run:
	@docker-compose exec php sh -c "vendor/bin/phpunit tests/"

postman-test:
	@docker-compose run postman_checks

pre-commit: phpcs clean-code psalm tests-run
	@: