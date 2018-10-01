build:
	docker-compose build --pull
	docker-compose up -d

start:
	docker-compose up -d

down:
	docker-compose down

shell:
	docker exec -it system-logger sh
