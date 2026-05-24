# PHP MySQL Smarty Blog

Initial bootstrap for a blog application built with plain PHP, MySQL and Smarty.

## Stack

- PHP 8.1+
- MySQL 8.0
- Smarty
- Docker

## Included In This Stage

- Docker-based local environment
- Composer configuration
- Base application config
- Public assets and Apache rewrite rules
- Writable runtime directories for Smarty cache/compiled templates

## Setup

Create a local environment file:

```bash
cp .env.example .env
```

Start containers:

```bash
docker compose up -d --build
```

Apply the database schema:

```bash
docker compose exec -T db sh -c 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' < database/schema.sql
```

Seed demo data:

```bash
docker compose exec -T app php database/seed.php
```

The application will be available at:

```text
http://localhost:8080
```

phpMyAdmin will be available at:

```text
http://localhost:8081
```
