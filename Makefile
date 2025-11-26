.PHONY: help build up down restart shell install test test-watch clean lint fix analyse check

help: ## Affiche l'aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Construit l'image Docker
	docker-compose build

up: ## Démarre le conteneur
	docker-compose up -d

down: ## Arrête le conteneur
	docker-compose down

restart: ## Redémarre le conteneur
	docker-compose restart

shell: ## Ouvre un shell dans le conteneur
	docker-compose exec php bash

install: ## Installe les dépendances Composer
	docker-compose exec php composer install

update: ## Met à jour les dépendances Composer
	docker-compose exec php composer update

dump-autoload: ## Régénère l'autoloader Composer
	docker-compose exec php composer dump-autoload

test: ## Lance les tests PHPUnit
	docker-compose exec php vendor/bin/phpunit

test-verbose: ## Lance les tests PHPUnit en mode verbeux
	docker-compose exec php vendor/bin/phpunit --verbose

test-coverage: ## Lance les tests avec couverture de code
	docker-compose exec php vendor/bin/phpunit --coverage-text

lint: ## Vérifie le style de code avec PHP CS Fixer (sans modifier)
	docker-compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff

fix: ## Corrige automatiquement le style de code avec PHP CS Fixer
	docker-compose exec php vendor/bin/php-cs-fixer fix

analyse: ## Analyse le code avec PHPStan
	docker-compose exec php vendor/bin/phpstan analyse

check: lint analyse test ## Vérifie tout (lint + analyse + tests)

clean: ## Nettoie les fichiers générés
	docker-compose exec php rm -rf vendor/.phpunit.cache
	rm -rf .phpunit.cache

setup: build up install ## Configuration complète du projet (build + up + install)
