all: build

build:
	docker-compose up -d
	docker-compose exec php composer install -n --no-dev