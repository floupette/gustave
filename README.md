# Chez Gustave

![Logo](logo.png)

[Specifications](https://docs.google.com/document/d/1n2IyRwiLYHipTCYBZB25tJW4XGaWm-z8f3i0Kxcx-pU/edit?usp=sharing)
[Assets](https://drive.google.com/drive/folders/180yJM1hKLHpg4uTajRZUu5odq5EVkdl2?usp=drive_link)

## Templates

Templates are branches of this repository.

- [Javascript server + React](https://github.com/Mathys-Gasnier/ChezGustave/tree/template-js)
- [Typescript server + React](https://github.com/Mathys-Gasnier/ChezGustave/tree/template-ts)
- [Php/CodeIgniter server + React](https://github.com/Mathys-Gasnier/ChezGustave/tree/template-ts)

## Starting a template

The only thing needed is [Docker](https://www.docker.com/products/docker-desktop/).

Go the the root of the template and run `docker compose up -d`.

This should start the server and the client.

The server should be accessible on `localhost:3630`, and the client on `localhost:5173`.

If you want to close them `docker compose kill` to close the server, client and database and `docker compose down` to delete the containers.

## Tests

### Server

To run server tests your need to run the `docker-compose.test.yml` instead of the normal `docker-compose.yml`

To do that you should run: `docker compose -f docker-compose.test.yml up -d`.

### Client

To run client tests you need to start docker as normal and then `cd` into the client directory and run `npx cypress open`.

This should open a browser where you can access, edit and create e2e tests.

## Guidelines

When you want to start working on something, be it a new feature a bug fix or tests you need to create a new branch to hold your changes.

Firstly you need to create the branch by doing `git checkout -b branch_type/branch_name`.

The `branch_type` should be one of: `features` for new features, `bugs` for bug fixes or `tests` for updating/adding tests.

The `branch_name` should describe what's being done, some example: `features/login-page`, `bugs/fixing-the-modal-component`, ...

When you have made a branch you can code as normal and commit regularly.

To push your changes you can use `git push -u origin branch_name`. `branch_name` being the whole branch name with the prefix (`features`, ...).

Once that is done you should be able to see the branch on the github repository.

When you are ready you can then create a pull request, and wait for a review.

You can always push commits to the branch when the pull request is created, they will be automaticly added to the pull request.