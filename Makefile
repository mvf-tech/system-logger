build:
	docker-compose build --pull
	docker-compose up -d

start:
	docker-compose up -d

down:
	docker-compose down

shell:
	docker exec -it system-logger sh

logs:
	docker-compose logs -f --tail=100

test:
	docker exec -it system-logger-tests phpspec run -f pretty -v

coverage:
	docker exec -it system-logger-coverage coverage "$(type)"

lint:
	docker exec -it system-logger-linter php-cs-fixer fix
	docker exec -it system-logger-linter phpcbf --standard=mvf_ruleset.xml || true
	docker exec -it system-logger-linter phpcs --standard=mvf_ruleset.xml

report:
	docker exec -it system-logger-metrics phpmetrics --report-html="." /code

.PHONY: build start down shell coverage report
