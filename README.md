# Rock star coffee Application

This application provides an API (Application Programming Interface) and 
performs ordering process.

## Index

* [Requirements](#requirements)
* [Structure](#structure)
* [Usage](#usage)
  * [Run the application](#run-application)
* [Tests](#tests)
* [Telescope](#telescope)
* [Background Jobs](#background-jobs)

## Structure

The project structure is as bellow

- .assets `The assets of the readme file are placed here`
- .web `The nginx configurations are placed here`
- .backend `The backend application configurations are placed here`
- [backend](./backend) `The backend application itself is placed here`
- .gitignore `The assets that should be ignored by git`
- docker-compose.yml `docker compose configuration`
- Makefile `This file is here to help working with the project`

## Requirements

You need `docker` and `docker-compose` in order to run this application.
If you don't have `make` on your operating system for running the application,
you need to read `Makefile` and do as `up` method says, otherwise you just need
to follow [Running](#run-application) section.

## Usage

In this section working with the application is described.

## Run Application

for running application you need to execute `up` method using `make` command
like bellow:

```shell
make up
```

## Tests

You can run tests outside of the application's container by `make` tool using
bellow command.

```shell
make test
```

# Telescope

The telescope is added to the project so you can see the notification for changed order status
is being processed and send to the user in the system which in this case all mails are **Logged**
instead of being truly send to anyone.

# Background Jobs

For handling queues the laravel built-in tool called `queue` is being used and after the backend
container did run, an execution command is triggered to run the `queue:work` command as an os
background job.

# Diagram

![Rock star coffee database diagram](.assets/rock-star-coffee.png)
