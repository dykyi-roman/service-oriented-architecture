#!/bin/bash
php bin/console --no-interaction doctrine:migrations:migrate
php bin/console --env=test --no-interaction doctrine:migrations:migrate