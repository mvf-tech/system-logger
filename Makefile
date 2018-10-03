build:
	docker-compose build --pull
	docker-compose up -d

start:
	docker-compose up -d

down:
	docker-compose down

shell:
	docker exec -it system-logger sh

test\:%:
	docker exec -it system-logger tests $@ "$(path)" "$(class)" "$(line)"

coverage:
	docker exec -it system-logger coverage

report:
	docker exec -it system-logger-metrics phpmetrics --report-html="." /code

.PHONY: build start down shell coverage report