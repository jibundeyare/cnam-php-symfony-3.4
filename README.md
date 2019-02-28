# CNAM PHP Symfony 3.4

## Install

Attention, les dépendances (le dossier `vendor`) ne sont pas enregistrées dans le repo.

Vous devez les installer par vous même après téléchargement du projet.

Rendez-vous dans le dossier dans lequel vous avez dézippé le fichier, puis tapez les commandes suivantes :

    cd cnam-php-symfony-3.4-master
    composer install

## Base de données

Le fichier SQL `cnam_bdmicro.sql` à la racine du projet contient un export de la BDD.
Vous devez importer cette BDD (avec PhpMyAdmin par exemple) avant de pouvoir utiliser ce projet.

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

Voici la ligne pour le user `root`, sans mot de passe, une connection au port mysql par défaut `3306` sur la même machine, la BDD `my_project` :

    DATABASE_URL=mysql://root:@127.0.0.1:3306/my_project

#### MAMP

Voici la ligne pour le user `root`, le mot de passe `root`, une connection au port mysql par défaut avec MAMP `8889` sur la même machine, la BDD `my_project` :

    DATABASE_URL=mysql://root:root@127.0.0.1:8889/my_project

#### Autre

Voici la ligne pour le user `root`, le mot de passe `123`, une connection au port mysql par défaut `3306` sur la même machine, la BDD `my_project` :

    DATABASE_URL=mysql://root:123@127.0.0.1:3306/my_project

## Serveur web

Vous devez lancer un serveur web pour pouvoir visualiser le projet.

À la racin du projet, tapez la commande suivante :

    php bin/console server:run

Puis ouvrez l'URL suivante avec votre navigateur web : [http://localhost:8000/](http://localhost:8000/)

## Contrôle continu février 2019

L'objectif de ce contrôle continu est de créer un nouveau contrôleur qui permet de gérer la liste des clients.
Pour ce faire, vous pouvez vous inspirer du contrôleur qui permet de gérer les catégories.

L'évaluation ne portera que sur la création de deux actions :

- la liste des clients (obligatoire)
- les détails d'un client (obligatoire)

Je ne vous en demande pas plus.
Mais si vous êtes motivé, vous pouvez ajouter les actions suivantes :

- création d'un nouveau client (optionnel)
- édition d'un client (optionnel)
- suppression d'un client (optionnel)

### Contraintes techniques

Le contrôleur des catégories se trouve dans le fichier `src/CategorieController.php`.

Le contrôleur des clients se trouve dans le fichier `src/ClientController.php`.

Les templates se trouvent dans le dossier `templates/client` :

- `templates/client/index.html.twig` affiche la liste des clients
- `templates/client/show.html.twig` affiche les détails d'un client

### Analyse

Le fichier SQL `cnam_bdmicro.sql` à la racine du projet contient un export de la BDD.
L'analyse ci-dessous correspond à cette BDD.

Mais si sou préférez, vous pouvez utiliser votre BDD.
Pensez alors à adapter les templates.

Pour vous aider à mieux cerner le problème voici une analyse des données.

#### Analyse de la page `client`

| champ     | name      | type    | balise          | obligatoire |
|-----------|-----------|---------|-----------------|-------------|
| numclient | numclient | int     | input type text | oui         |
| nom       | nom       | varchar | input type text | oui         |
| ville     | ville     | varchar | input type text | oui         |

### Livraison

Ce travail est à livrer le vendredi 15/03/2018 à 24h00 au plus tard.
Pour les retardataires, un délai supplémentaire jusque dimanche 17/03/2019 à 24h00 sera toléré, mais je serai beaucoup plus exigeant sur la qualité du travail.
Autrement dit, si vous livrez en retard, le travail doit être impécable.

La livraison doit être faite sous la forme d'un fichier zip contenant les fichiers et dossiers suivants :

- le dossier `src/`
- le dossier `templates/`
- un export SQL de votre BDD si elle a une structure différente de la mienne

Vous devez déposer le fichier sur mon espace SFTP.
Si vous n'y parvenez pas, en dernier recours, envoyez-le moi par mail.

### Critères d'évaluation

- respect des consignes : structure du dossier projet, nom des pages, nom des champs, fonctionnalités
- code style : lisibilité, indentation, nommage des variables, lignes vides, espaces après ou avant mot-clés
- absence de bug (HTML, PHP et Twig)

