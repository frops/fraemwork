#!/usr/bin/env bash
docker-compose exec php cd /var/www/app, php vendor/bin/phpunit --coverage-tex=t.txt src/Simplex/Tests --color=always --coverage-text