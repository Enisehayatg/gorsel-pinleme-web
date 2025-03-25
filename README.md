# backend

[![Lint](https://github.com/the-cans-group/construction-finance-backend/actions/workflows/lint.yml/badge.svg)](https://github.com/the-cans-group/construction-finance-backend/actions/workflows/lint.yml) [![Test](https://github.com/the-cans-group/construction-finance-backend/actions/workflows/test.yml/badge.svg)](https://github.com/the-cans-group/construction-finance-backend/actions/workflows/test.yml) 

## How To Set Up Local Environment

### Sail

In order to set up the local environment with Laravel Sail, follow the steps below;

#### Step 1

Copy the environment file.

```bash
cp .env.example .env
```

#### Step 2

Uncomment the Mysql lines and comment the sqlite lines in the `.env` file.

```bash
...
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=mysql
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=sail
# DB_PASSWORD=password
...
```

#### Step 3

```bash
composer install
./vendor/bin/sail up -d
```

## API Documentation

```bash
php artisan scribe:generate
```

Access the documentation with `/docs` route.
