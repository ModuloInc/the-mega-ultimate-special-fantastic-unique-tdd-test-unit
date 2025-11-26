# Projet PHP avec Tests Unitaires

Projet PHP 8.3 avec Composer, Docker, PHPUnit 12, PHP CS Fixer et PHPStan.

## Prérequis

- Docker et Docker Compose installés

## Installation

```bash
make setup
```

Cette commande effectue automatiquement :
1. Construction de l'image Docker
2. Démarrage du conteneur
3. Installation des dépendances Composer

## Commandes principales

```bash
make test          # Lance les tests PHPUnit
make test-coverage # Lance les tests avec couverture de code
make check         # Vérifie tout (lint + analyse + tests)
```

## Documentation

📖 **[Documentation complète MyWeeklyAllowance](./docs/MYWEEKLYALLOWANCE.md)**
