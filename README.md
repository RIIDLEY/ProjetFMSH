# Projet FMSH
***

L'objectif de ce projet est de réaliser une infrastructure permettant d'envoyer des documents (vidéo, audio ou document numérique) et de les visualiser sous forme de graphique en réseau.

## Technologies utilisés

* PHP
* Python
* [Boostrap](https://getbootstrap.com/)
* [SigmaJS](https://www.sigmajs.org/)/[Linkurious](https://github.com/Linkurious/linkurious.js/tree/develop)  

## Installation

Il faut créer un nouvelle utilisateur MySQL/MariaDB avec comme login : "stage_fmsh" et comme mot de passe : "YcqB)/)HRB7(.hZo"
Par la suite lancez le script SQL DB.sql qui se trouve dans le dossier Utils.
Il permet de créer la base de données ainsi que la table nécessaire.
Déposez le répertoire contenant le site dans votre dossier qui permet de le lancer avec Apache.

## L'espace visualisation

Cet espace est accessible à tout le monde. Il donne accès à chacun à un outil de recherche de documents par mots-clés. Ces documents seront listés et disposés sous forme de graphique en réseau.
Ce graphique est interactif, un utilisateur peut cliquer sur chaque noeud et obtenir plus d'informations sur ce dernier.

## L'espace membre

Cet espace est réservé aux personnes administrant cette infrastructure. Chaque personne ayant accès à cet espace, peut alimenter l'infrastructure en envoyant des documents.
Ces documents sont envoyé traité et répertorié. Ils seront accessibles avec l'outil de visualisation.


## Identifiant/mot de passe

Pour la base de données
* Login : stage_fmsh
* Mot de passe : YcqB)/)HRB7(.hZo

Login administration de l'infrastructure
* Login : coucou
* Mot de passe : coucou
