# Laravel Technical Exam API

This project imports all JSONPlaceholder data into a normalized MySQL database using an Artisan command, then exposes authenticated REST APIs to read the stored data.

## Features

- Laravel 12 + Eloquent models/relationships
- Normalized database design for:
  - users, addresses, companies
  - posts, comments
  - albums, photos
  - todos
- Import command:
  - `php artisan sync:jsonplaceholder`
  - `php artisan sync:jsonplaceholder --truncate`
- Authenticated REST API with HTTP Basic Auth (`auth.basic`)
- Postman collection included: `postman/Technical-Exam.postman_collection.json`
- Dockerized app + MySQL via `docker-compose.yml`
- Automated tests for import command and API authentication

## Database Architecture

Main table strategy:

- Keep local auth users in default `users` table.
- Store JSONPlaceholder users in `placeholder_users` (with unique `external_id`).
- Split one-to-one details into:
  - `placeholder_addresses`
  - `placeholder_companies`
- Use separate tables for content:
  - `placeholder_posts`
  - `placeholder_comments`
  - `placeholder_albums`
  - `placeholder_photos`
  - `placeholder_todos`

Normalization choices:

- `external_id` is unique per JSONPlaceholder entity to support idempotent imports.
- Foreign keys enforce referential integrity.
- One-to-one constraints are enforced with unique `placeholder_user_id` in address/company tables.

## Local Setup (Without Docker)

1. Install dependencies:
   - `composer install`
2. Prepare environment:
   - `cp .env.example .env`
   - Update DB settings in `.env` (MySQL recommended)
   - `php artisan key:generate`
3. Run migrations and create API user:
   - `php artisan migrate --seed`
4. Import JSONPlaceholder data:
   - `php artisan sync:jsonplaceholder`
5. Start app:
   - `php artisan serve`

Default seeded API credentials:

- Email: `api@example.com`
- Password: `password`

## Docker Setup

1. Build and start services:
   - `docker compose up --build`
2. App URL:
   - `http://localhost:8000`

What happens on container startup:

- `.env` is created if missing
- app key is generated
- migrations + seeders are executed
- JSONPlaceholder data is imported
- app server starts on port 8000

MySQL is exposed on host port `3307`.

## API Authentication Guide

Authentication scheme: HTTP Basic Auth.

Use these credentials in Postman or any REST client:

- Username: `api@example.com`
- Password: `password`

If no credentials are sent, API returns `401 Unauthorized`.

## API Endpoints

All endpoints are prefixed with `/api/v1` and require Basic Auth.

- `GET /users`
- `GET /users/{externalId}`
- `GET /posts`
- `GET /posts/{externalId}`
- `GET /comments`
- `GET /comments/{externalId}`
- `GET /albums`
- `GET /albums/{externalId}`
- `GET /photos`
- `GET /photos/{externalId}`
- `GET /todos`
- `GET /todos/{externalId}`

Optional query parameter:

- `per_page` (default: 25, max: 100)

## Postman Testing

1. Import collection:
   - `postman/Technical-Exam.postman_collection.json`
2. Ensure `localhost:8000` is running.
3. Run requests directly (Basic Auth is prefilled in the collection).

## Useful Commands

- Import data:
  - `php artisan sync:jsonplaceholder`
- Re-import from scratch:
  - `php artisan sync:jsonplaceholder --truncate`
- Run tests:
  - `php artisan test`

## Efficiency Notes

- Data import uses bulk `upsert()` operations for idempotent and efficient syncing.
- External-to-local ID maps avoid duplicate inserts and maintain foreign-key consistency.
- API endpoints use pagination and eager loading to reduce query count.
