include .env

build: ## Сборка и запуск контейнеров
	docker-compose --env-file .env up -d --build --force-recreate --remove-orphans

ssh: ## Shell php контейнера
	docker exec -it test-php bash

start: ## Запуск теста
	docker exec test-php php test.php
