include .env

all: install compose

install: .env
	@docker-compose build
	@docker-compose up -d

uninstall: .env
	@docker-compose down
	@docker system prune -af

restart: .env
	@docker-compose down
	@docker-compose up -d

compose: .env
	@docker-compose exec service_php composer install
