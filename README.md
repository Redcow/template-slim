# modelApiPHP

/!\ Todo : les repositories ne doivent pas tous découler d'un abstract repository, privilégier la composition au lieu de l'héritage sur ce cas


## Debuggage

L'image PHP est à utiliser pour le développement, xdebug est installé et pret à être utilisé.

Se référer à la vidéo suivante pour l'utiliser avec votre IDE: https://grafikart.fr/tutoriels/xdebug-docker-php-2014

## Les variables d'environnement (app/config)

L'EnvProvider contient les actions de référencement des variables d'environnement nécessaires à l'application en alertant si une variable est manquante ou attribuant une valeur par défaut si renseignée.

Chargement initié dans le index.php via app/config/_index.php


## Les middlewares (app/config)

Les middlewares peuvent être implémentés de différentes façons

- Pour l'ensemble de l'application
- Pour un groupement de routes
- Pour une route en particulier

Ils peuvent intervenir avant ou après avoir transmis la requête

- Le Firewall : Il intervient en que middleware d'application, il vérifie la validité du token et sa présence en cas de route non publique
- Le RouteValidator : Il est attaché automatiquement à l'ensemble des routes de l'application, il parcourt les paramètres de requêtes avec les validateurs renseignés dans les controllers (voir Controller\Security\SignInController.php)
- L'ErrorHandler : Middleware partiuclier, il est toujours chargé en dernier, capte les exceptions levées lors du traitement de la requête, si c'est une erreur inconnue (non CodeMessageError) il a pour rôle d'alerter l'équipe du projet.

Le chargement des middlewares liés à l'ensemble de l'application se fait via app/middlewares/_index.php


## Les routes (app/routes)

Décrit les URLs disponibles, ici, les routes sont groupées par entité métier.

Chargement initié dans le index.php via app/routes/_index.php


## Les controlleurs (app/contollers)

Prennent en charge les routes décrites au dessus.
Les services peuvent y être autoloadés dans le constructeur du controller grâce au conteneur de service.
Ils définissent également les validations de paramètres (via des attributs QueryParam) pour la route.


## Conteneur de services (app/services)

Liste les différents services pouvant être autoloadés dans les controlleurs

- Services Repository : Service responsable des échanges/actions avec la persistance des données (base de données dans cet exemple)
- Service Mailer : Service d'envoi de mail
- Service JwtManager : Service de création et de décryptage de JWT

Chaque service englobe un package composer importé (par exemple un mailer) ou des services faits maison, leur logique est simplifiée pour faciliter et homogénéiser l'utilisation dans le code. Si on souhaite remplacer l'implémentation d'un service par un autre (exemple PHP Mailer au lieu Symfony Mailer), la modification ne se fera qu'à un seul endroit, dans le conteneur de services.

Le conteneur de service permet de faciliter la construction des services, ils sont ensuite autoloadés dans les controllers au besoin.

Le conteneur de services est la boite à outils du développeur.

/!\ Les services ne seront pas chargés s'ils ne sont pas appelés dans le controller concerné par la requête, ce n'est pas consommateur. /!\

Création du le conteur de services via app/services/_index.php


## Le domaine / model / business (app/model)

Contient l'ensemble du code métier, c'est à dire les fonctionnalités
Chaque grand thème métier est rangé dans un dossier qui lui est propre.

L'entité correspond généralement à une table en base de données, les propriétés liées à une colonne de table portent l'attribut DbColumn.

Les cas d'usage (c'est à dire ce que FAIT l'application) sont répertoriés en sous dossier, on va y retrouver la structure définie suivante

- le cas d'usage en lui même écrit "*fonction* *Entité*"
- l'input (ou *request*)
- l'output (ou *response*)
- un dossier 'interfaces' contenant l'ensemble des interfaces utilisées dans le cas d'usage

Dans un souci d'architecture pérenne, le cas d'usage doit être libre de toute dépendance/détails technique

Ainsi quand il fait référence à des besoins de services, le cas d'usage mentionne des interfaces (souvent comparés à des *contrats* ) afin d'imposer une façon de fonctionner sans se lier à une classe dite concrète.

De la même façon, pour une réutilisation plus simple du cas d'usage, le résultat de celui-ci ne doit jamais être formaté, ni retourné, le cas d'usage se content de stocker ses résultats bruts dans l'objet de sortie l'output.

Afin d'éviter toute régression, le cas d'usage impose ses informations d'entrées par l'input. L'input a l'avantage d'être de la forme d'un objet

- Contrairement à un tableau associatif, on a une meilleure visibilité sur les informations requises et leur type attendu.
- Contrairement à une liste d'arguments qui doit rester stable dans le temps, l'input peut évoluer et proposer de nouvelles informations requises au fil des maintenances sans désordonner le code.

## L'infrastructure (app/infrastructure)

L'infrastructure contient l'ensemble des détails techniques qui sont au service du code métier, des services ou de l'architecture.
Ces détails techniques vont donc implémenter les interfaces métier pour répondre aux exigences des cas d'usage.

... todo lister les  détails techniques contenus dans l'Infrastructure

## Gestion des schémas de base de données (sql)

Les scripts sql situés dans le dossier sql à la racine sont éxécutés par ordre alphabétique à la génération des containers docker.

- 1_database.sql : Génère les bases de données utilisées
- 2_schemas.sql : Génère les tables utilisées
- 3_data.sql : Insère les données initiales


## Lancement des tests (terminal du container docker)

Pour déclencher les tests :

- Editer dans le fichier composer.json les variables d'environnement pour pointer sur les bases de données de tests.

exemple : "@putenv DB_NAME=library_test"

- Executer dans le terminal : composer run-script test


## L'écriture de tests (test)

Les tests sont gérés avec le package Behat permettant de faire du BDD (Behavior Driven Development).
Cela permet de générés des scénarios lisibles.
Si un nouveau thème métier doit être créé, il faut l'ajouter au fichier behat.yml puis taper la commande :
- vendor/bin/behat --init

L'écriture d'un test se fait via un fichier .feature

Une fois le test écrit, il faut taper la commande : 
- vendor/bin/behat --dry-run --append-snippets

Cela va avoir pour effet de générer les tests à écrire pour pouvoir tester les scénarios écrits.