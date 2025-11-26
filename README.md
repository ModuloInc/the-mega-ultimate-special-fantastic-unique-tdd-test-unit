# Projet PHP avec Tests Unitaires

Projet PHP 8.3 avec Composer, Docker, PHPUnit 12, PHP CS Fixer et PHPStan.

## 📦 Projets inclus

### 1. MyWeeklyAllowance - Système de Gestion d'Argent de Poche

Module de gestion d'argent de poche pour adolescents développé en **TDD (Test Driven Development)**.

**Fonctionnalités** :
- ✅ Création de comptes pour adolescents
- ✅ Dépôt d'argent sur les comptes
- ✅ Enregistrement de dépenses
- ✅ Allocation hebdomadaire automatique

**Architecture** :
- Domain Layer : `Teenager`, `Account`, `Transaction`
- Service Layer : `AccountService`, `AllowanceManager`
- Tests unitaires complets avec PHPUnit

📖 **[Documentation complète](./docs/MYWEEKLYALLOWANCE.md)**

### 2. Module Mathématique (Exemple initial)

Module mathématique de base avec tests unitaires (addition, soustraction, multiplication, division).

## Prérequis

- Docker et Docker Compose installés

## Installation

1. Construire l'image Docker :
```bash
docker-compose build
```

2. Démarrer le conteneur :
```bash
docker-compose up -d
```

3. Installer les dépendances Composer :
```bash
docker-compose exec php composer install
```

## Commandes disponibles

### Avec Makefile (recommandé)

```bash
make help          # Affiche toutes les commandes disponibles
make setup         # Configuration complète (build + up + install)
make test          # Lance les tests PHPUnit
make test-coverage # Lance les tests avec couverture de code
make lint          # Vérifie le style de code (sans modifier)
make fix           # Corrige automatiquement le style de code
make analyse       # Analyse le code avec PHPStan
make check         # Vérifie tout (lint + analyse + tests)
```

### Commandes directes

```bash
# Tests
docker-compose exec php vendor/bin/phpunit

# PHP CS Fixer (formatage)
docker-compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff  # Vérification
docker-compose exec php vendor/bin/php-cs-fixer fix                   # Correction

# PHPStan (analyse statique)
docker-compose exec php vendor/bin/phpstan analyse
```

## Structure du projet

```
.
├── src/
│   └── App/
│       ├── Domain/                        # MyWeeklyAllowance - Domain Layer
│       │   ├── Account.php
│       │   ├── Teenager.php
│       │   ├── Transaction.php
│       │   └── Exception/
│       ├── Service/                       # MyWeeklyAllowance - Service Layer
│       │   ├── AccountService.php
│       │   └── AllowanceManager.php
│       ├── Mathematique.php              # Module mathématique
│       └── Exception/
├── tests/
│   ├── Domain/                           # Tests MyWeeklyAllowance
│   ├── Service/
│   └── MathTest.php                      # Tests mathématiques
├── docs/
│   └── MYWEEKLYALLOWANCE.md             # Documentation MyWeeklyAllowance
├── composer.json
├── phpunit.xml
├── .php-cs-fixer.php
├── phpstan.neon
├── Makefile
├── Dockerfile
└── docker-compose.yml
```

## Outils de qualité de code

- **PHP CS Fixer** : Formatage et style de code (équivalent Prettier pour PHP)
- **PHPStan** : Analyse statique de code (équivalent ESLint pour PHP)

Ces outils garantissent la cohérence du code et détectent les erreurs potentielles avant l'exécution.

