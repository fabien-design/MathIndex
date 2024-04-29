# MathIndex

Projet de fin d'année BTS2 - Symfony

## Identifiants

Pour accéder à différentes parties de l'application, voici les identifiants par défaut :

| Email                     | Mot de passe | Rôles                                    |
|---------------------------|--------------|------------------------------------------|
| student@example.com       | xxx          | ROLE_STUDENT                             |
| mathteacher@example.com   | xxx          | ROLE_TEACHER                             |
| frteacher@example.com     | xxx          | ROLE_TEACHER                             |
| admin@example.com         | xxx          | ROLE_ADMIN                               |

## Commandes Makefile

Pour simplifier la gestion du projet, voici quelques commandes Makefile :

| Commande               | Utilisation                                                                                                      |
|------------------------|------------------------------------------------------------------------------------------------------------------|
| `make install`         | Installe toutes les dépendances, configure la base de données, exécute les migrations et charge les fixtures.   |
| `make dcu`             | Arrête, supprime et relance les conteneurs Docker afin de (re)démarrer le projet.                                 |
| `make fixtures`        | Charge les fixtures dans la base de données.                                                                      |
| `make bash`            | Ouvre un terminal bash dans le conteneur Symfony.                                                                 |

## 🚀 Installation avec Docker 

Si vous préférez utiliser Docker pour gérer votre environnement de développement, voici comment procéder :

1. [Installez Docker](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-22-04) sur votre machine.

2. Initialisez le projet en exécutant la commande suivante à la racine du projet :
```bash
make install
```
3. Une fois l'installation terminée, lancez le projet avec Docker en utilisant la commande :
```bash
make dcu
```

### Initialisation du projet de A-Z avec Docker

Pour ceux qui préfèrent contrôler chaque étape de l'initialisation, voici la procédure détaillée :

1. Lancez tous les conteneurs Docker en utilisant la commande :
```bash
docker compose up
```

2. Accédez au bash du conteneur Symfony de Docker pour exécuter des commandes spécifiques :
```bash
docker compose exec symfony bash
```

3. À l'intérieur du conteneur Symfony, effectuez les étapes suivantes :
```bash
composer install
  
php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force

php bin/console doctrine:fixtures:load -n
```

4. Lancez la compilation de Sass à partir du terminal SSH :
```bash
npm run build
```

5. Si vous utilisez des messager workers (pour les mails par exemple), lancez-les avec la commande suivante :
```bash
bin/console messenger:consume async
```

## 🐌 Installation sans Docker

Si vous préférez ne pas utiliser Docker, vous pouvez toujours configurer votre environnement localement :

1. Initialisez le projet en exécutant la commande suivante à la racine du projet :
```bash
composer install
```

2. Compilez les fichiers Sass en exécutant :
```bash
npm init
npm install @symfony/stimulus-bridge
npm run watch
```

3. Créez et initialisez la base de données :
```bash
symfony console doctrine:database:create
symfony console doctrine:schema:update
symfony console doctrine:fixtures:load
```

Avec ces instructions, vous devriez être en mesure de démarrer rapidement le projet, que ce soit avec Docker ou sans.

## Auteurs

- [@Lucas Dupas](https://github.com/Magiks0)

- [@Fabien Rozier](https://github.com/fabien-design)

- [@Raphaël Toursel](https://github.com/TWhiteShadow)
