# CNAM PHP Symfony 3.4

## Install

Attention, les dépendances (le dossier `vendor`) ne sont pas enregistrées dans le repo.

Vous devez les installer par vous même après téléchargement du projet.

Rendez-vous dans le dossier dans lequel vous avez dézippé le fichier, puis tapez les commandes suivantes :

    cd cnam-php-symfony-3.4-master
    composer install

## Configuration de l'accès à la base de données

Créez un fichier `.env.local` à la racine du projet.

Ouvrez le fichier `.env` à la racine du projet et copiez le bloc :

    APP_ENV=dev
    APP_SECRET=24209e2e73cff25fd43b74b21cf4e173

et collez-le dans le fichier `.env.local`.

Copiez aussi le bloc suivant dans le fichier `.env` :

    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

et collez-le dans le fichier `.env.local`.

Ensuite, modifiez la ligne `DATABASE_URL` du fichier `.env.local` pour que Symfony puisse accéder à votre BDD.

Vous devez modifier les éléments `db_user`, `db_password`, `127.0.0.1`, `3306` et `db_name` avec les informations qui correspondent à votre configuration.

### Exemples

Voici la ligne originale :

    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

#### Wamp

Voici la ligne pour le user `root`, sans mot de passe, une connection sur le port `3306` (mysql) sur le même serveur, la BDD `my_project` :

    DATABASE_URL=mysql://root:@127.0.0.1:3306/my_project

#### MAMP

Voici la ligne pour le user `root`, le mot de passe `root`, une connection sur le port `8889` (mysql) sur le même serveur, la BDD `my_project` :

    DATABASE_URL=mysql://root:root@127.0.0.1:8889/my_project

#### Autre

Voici la ligne pour le user `root`, le mot de passe `123`, une connection sur le port `3306` (mysql) sur le même serveur, la BDD `my_project` :

    DATABASE_URL=mysql://root:123@127.0.0.1:3306/my_project


