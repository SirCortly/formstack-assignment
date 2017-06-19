# Formstack Assignment
A small API to create, read, update, and delete Users.

## Overview

The API exposes the following routes:
```
GET /users           List Users
GET /users/{id}      Get User by ID
POST /users          Create new User
PUT /users/{id}      Update existing User
DELETE /users/{id}   Delete User
```
 
The model layer consists of two types of objects, Domain Objects extending `AbstractDomainObject` and Data Mappers extending `AbstractDataMapper`. Domain Objects are responsible for encapsulating data and business logic related to a specific entity (ex. User). Data Mappers are responsible for mapping Domain Objects to and from the database.

The view consists of a View Transformer extending `AbstractViewTransformer`. This class requires a `transform` method that will take an entity (ex. User) and transform it into the appropriate output format.

## Setting Up
In order to run this project you will first need to clone this repository:
```
git clone https://github.com/SirCortly/formstack-assignment.git
```

Change directory and run vagrant up to initialize the Vagrant box:
```
cd formstack-assignment/
vagrant up
```

Copy .env.example to .env: 
```
cp .env.example .env
```
*The .env file will store our environment variables and make them available via [getenv()](https://github.com/vlucas/phpdotenv)*

SSH into Vagrant box and navigate to shared vagrant directory:
```
vagrant ssh
cd /vagrant
```

Install dependencies:
```
composer install
```

Run [Phinx](https://phinx.org/) migrations:
```
composer migrate
```

## Testing
**Coverage reports will be generated when tests are run and can be viewd by opening `/test/coverage/index.html`.**
**Only unit tests contribute to the coverage report.**

In order to run the full test suite, navigate to the project's root directory and run:
```
composer test
```

In order to run unit tests:
```
composer unit
```

In order to run functional (integration) tests:
```
composer functional
```

*Make sure tests are run from inside vagrant box*


## Migrations
In order to run migrations, navigate to the project's root directory and run:
```
composer migrate
```

To rollback migrations run: 
```
composer rollback
```

You can seed the database with test data by running:
```
composer seed
```

*Make sure migrations are run from inside vagrant box*
