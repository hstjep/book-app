# Book App

## Setup
```bash
$ cd book-app/
$ composer install
```
### Usage
**Option 1.** [Download Symfony CLI](https://symfony.com/download) and run this command:

```bash
$ symfony server:start
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

**Option 2.** 
On your local machine, run this command to use the built-in PHP web server:

```bash
$ php -S localhost:8000 -t public/
```

Then access the application in your browser at the given URL (http://localhost:8000 by default).

### Requirements
- PHP >= 8.0

## Docker Setup
```bash
$ cd book-app/
$ docker build -t book-app . 
```

### Usage
```bash
$ docker run -p 8000:8000 book-app 
```

Then access the application in your browser at the given URL (http://localhost:8000 by default).

## Documentation
### Create author command
The command requires the following variables to be set in the env.local file:
- API_USER_EMAIL=example@mail.com
- API_USER_PASSWORD=password

### Usage

```bash
php bin/console app:create-author
```
