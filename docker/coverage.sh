#!/usr/bin/env bash

vendor/bin/php-cs-fixer fix
vendor/bin/phpcbf --standard=mvf_ruleset.xml
vendor/bin/phpcs --standard=mvf_ruleset.xml
docker-php-ext-enable xdebug
COMMAND="vendor/bin/phpspec run -f pretty -v"
shape exec --command "$COMMAND" --shape phpspec.yml extensions=phpspec-coverage.yml
rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
