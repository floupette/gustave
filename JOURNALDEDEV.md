# Chez Gustave

## INSTALLATION DU PROJET

- Prise en main du Cahier des charges
- Outils utilisés : Docker desktop, VSC, PG Admin, Docker, DB Schema.
- Alex : Utilisation du template CODEIGNITER 4 pour :
    installer les images docker avec `docker compose up -d`  (pg admin, client, serveur, base de données postgres )
    Realiser la connexion a la base de donnée
    Realiser le CRUD
    Authentification commencée
- De mon côté pour installer le projet, je n'ai eu qu'a :
- Accepter l'invitaion GITHUB
- Git Pull le repository
- Dans le terminal docker dans container server ( exec ) `php spark migrate` ou directement dans le terminal de VSC `docker exec database php spark migrate`

## Seeds

- Je vais faire mes fichiers seeds afin de remplir ma base de données