# MathIndex

Projet fin d'année BTS2 - Symfony 

## Installation

Initialisation du projet

```bash
  composer install
```

Lancement de la compilation de sass

```bash
  npm init
  
  npm install @symfony/stimulus-bridge
  
  npm run watch
```

Lancement de la compilation de sass sur terminal SSH

```bash
  npm run build
```

Initialisation de la base de données

```bash
  symfony console doctrine:database:create
  
  symfony console doctrine:schema:update
  
  symfony console doctrine:fixtures:load
```
