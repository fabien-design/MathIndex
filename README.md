# MathIndex

Projet de fin d'année BTS2 - Symfony

```bash
git clone https://github.com/fabien-design/MathIndex.git
```
[Url en ligne](https://mathindex.lyceestvincent.fr)

## Identifiants

Pour accéder à différentes parties de l'application, voici les identifiants par défaut&nbsp;:

| Email                     | Mot de passe | Rôles                                    |
|---------------------------|--------------|------------------------------------------|
| student@example.com       | xxx          | ROLE_STUDENT                             |
| mathteacher@example.com   | xxx          | ROLE_TEACHER                             |
| frteacher@example.com     | xxx          | ROLE_TEACHER                             |
| admin@example.com         | xxx          | ROLE_ADMIN                               |

## Commandes Makefile

Pour simplifier la gestion du projet, voici quelques commandes Makefile&nbsp;:

| Commande               | Utilisation                                                                                                      |
|------------------------|------------------------------------------------------------------------------------------------------------------|
| `make install`         | Installe toutes les dépendances, configure la base de données, exécute les migrations, charge les fixtures et démarre le projet.    |
| `make dcu`             | Arrête, supprime et relance les conteneurs Docker afin de (re)démarrer le projet.                                |
| `make fixtures`        | Charge les fixtures dans la base de données.                                                                     |
| `make bash`            | Ouvre un terminal bash dans le conteneur Symfony.                                                                |

## 🚀 Installation avec Docker 

Si vous préférez utiliser Docker pour gérer votre environnement de développement, voici comment procéder&nbsp;:

1. [Installez Docker](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-22-04) sur votre machine.

2. Initialisez le projet en exécutant la commande suivante à la racine du projet (le projet sera lancé automatiquement)&nbsp;:
```bash
make install
```

### Initialisation du projet de A-Z avec Docker

Pour ceux qui préfèrent contrôler chaque étape de l'initialisation, voici la procédure détaillée&nbsp;:

1. Lancez tous les conteneurs Docker en utilisant la commande&nbsp;:
```bash
docker compose up
```

2. Accédez au bash du conteneur Symfony de Docker pour exécuter des commandes spécifiques&nbsp;:
```bash
docker compose exec symfony bash
```

3. À l'intérieur du conteneur Symfony, effectuez les étapes suivantes&nbsp;:
```bash
composer install
  
php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force

php bin/console doctrine:fixtures:load -n
```
4. Accédez au bash du conteneur Node de Docker pour lancez la compilation de Sass&nbsp;:
```bash
docker compose exec node npm run dev
```

5. Si vous utilisez des messager workers (pour les mails par exemple), lancez-les avec la commande suivante&nbsp;:
```bash
docker compose exec symfony php bin/console messenger:consume async
```

## 🐌 Installation sans Docker

Si vous préférez ne pas utiliser Docker, vous pouvez toujours configurer votre environnement localement&nbsp;:

1. Initialisez le projet en exécutant la commande suivante à la racine du projet&nbsp;:
```bash
composer install
```
OU
```bash
php composer.phar install
```

2. Compilez les fichiers Sass en exécutant&nbsp;:
```bash
npm init
npm install @symfony/stimulus-bridge
npm run watch
```

3. Créez et initialisez la base de données&nbsp;:
```bash
symfony console doctrine:database:create
symfony console doctrine:schema:update --force
symfony console doctrine:fixtures:load -y
```
OR
```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load -y
```

Avec ces instructions, vous devriez être en mesure de démarrer rapidement le projet, que ce soit avec Docker ou sans.

## 🌐 Accès au projet

[Accès au site](http://127.0.0.1:8001)&nbsp;:
```bash
127.0.0.1:8001
```
[Accès à la db](http://127.0.0.1:8888) avec phpmyadmin (server: mariadb, name: root, pass: root)&nbsp;:
```bash
127.0.0.1:8888
```
[Accès à mailcatcher](http://127.0.0.1:1080) pour vérifier le mot de passe oublié&nbsp;:
```bash
127.0.0.1:1080
```

## Auteurs

- [@Lucas Dupas](https://github.com/Magiks0)

- [@Fabien Rozier](https://github.com/fabien-design)

- [@Raphaël Toursel](https://github.com/TWhiteShadow)
