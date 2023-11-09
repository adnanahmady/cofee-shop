# Rock Star Frontend

The visual illustration of the application.
This project is being handled by the dockerized
container project, so to prevent any inconsistancy
in running and installing packages you need to
use the higher order project.

# Index

- [Dockerized Container](#dockerized-container)
    - [Starting The Container](#starting-the-container)
    - [Loggin Into The Container](#loggin-into-the-container)
- [The Project Commands](#the-project-commands)
    - [Start The Project](#start)
    - [Build The Project](#build)
    - [Test The Project](#testing)
    - [Code Style Fixers/Checkers](#code-style-fixers)

# Dockerized Container

## Starting The Container

By starting the higher order dockerized project
all of required services including `backend` and
`frontend` will run.

You can start the main project using bellow command.

```shell
make up
```

or you can use the docker directly like bellow command.

```shell
docker-compose up -d
```

## Loggin Into The Container

You can access inside the container to perform
`npm` commands using bellow command.

```shell
make shell service=frontend run=ash
```

or if you don't have `make`, you can access inside
the container by this command too.

```shell
docker-compose exec frontend ash
```

# The Project Commands

## Start

You can start the project using bellow command.

```shell
npm run start
```

or simply

```shell
npm start
```

## Build

To build the project you need to run bellow command.

```shell
npm run build
```

## Code Style Fixers

The project uses `eslinter` to unify the code style,
and you can fix the style using bellow command.

```shell
npm run fix
```

## Testing

You can run the tests using bellow command.

```shell
npm run test
```
