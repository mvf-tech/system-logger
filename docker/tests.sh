#!/usr/bin/env bash

input=$1
arr=(${input//:/ })
URI=$2
CLASS=$3
LINE=$4

if [ ${arr[1]} = "lint" ]; then
    vendor/bin/php-cs-fixer fix
    vendor/bin/phpcbf --standard=mvf_ruleset.xml
    vendor/bin/phpcs --standard=mvf_ruleset.xml
elif [ ${arr[1]} = "coverage" ]; then
    vendor/bin/php-cs-fixer fix
    vendor/bin/phpcbf --standard=mvf_ruleset.xml
    vendor/bin/phpcs --standard=mvf_ruleset.xml
    docker-php-ext-enable xdebug
    COMMAND="vendor/bin/phpspec run -f pretty -v"
    RXCLASSES="Classes: *(?P<classes>\d{1,3}\.\d{2})%"
    RXMETHODS="Methods: *(?P<methods>\d{1,3}\.\d{2})%"
    RXLINES="Lines: *(?P<lines>\d{1,3}\.\d{2})%"
    REGEXP="$RXCLASSES.*\n.*$RXMETHODS.*\n.*$RXLINES"
    shape exec --command "$COMMAND" --regexp "$REGEXP" --shape phpspec.yml extensions=phpspec-test.yml
    rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
    test-coverage
elif [ ${arr[1]} = "unit" ]; then
    vendor/bin/php-cs-fixer fix
    vendor/bin/phpcbf --standard=mvf_ruleset.xml
    vendor/bin/phpcs --standard=mvf_ruleset.xml
	if [ "" != "${URI}" ]; then
		vendor/bin/phpspec run spec/${URI} -f pretty -v
	elif [ "" != "${CLASS}" ] && [ "" != "${LINE}" ]; then
		vendor/bin/phpspec run spec/${CLASS}Spec.php:${LINE} -f pretty -v
	elif [ "" != "${CLASS}" ]; then
		vendor/bin/phpspec run spec/${CLASS}Spec.php -f pretty -v
	else
		vendor/bin/phpspec run -f pretty -v
	fi
elif [ ${arr[1]} = "create" ]; then
	if [ ${arr[2]} = "unit" ]; then
		vendor/bin/phpspec desc MVF/$(/app/scripts/helpers/namespace.php)/${CLASS}
	fi
elif [ ${arr[1]} = "destroy" ]; then
	if [ ${arr[2]} = "unit" ]; then
		rm spec/${CLASS}Spec.php
		rm src/${CLASS}.php
	fi
fi
