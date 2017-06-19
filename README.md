# Formstack Assignment
A small API to create, read, update, and delete Users.

## Setting Up
In order to run this project you will first need to clone this repository:
```
git clone https://github.com/SirCortly/formstack-assignment.git
```

Change directory and run vagrant up initialize the Vagrant box:
```
cd formstack-assignment/
vagrant up
```

Copy .env.example to .env: 
```
cp .env.example .env
```
The .env file will store our environment variables and make them available via [getenv()](https://github.com/vlucas/phpdotenv)

SSH into Vagrant box and navigate to shared vagrant directory:
```
vagrant ssh
cd /vagrant
```

