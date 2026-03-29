#!/usr/bin/env sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate --force

if [ "${DB_CONNECTION}" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
    until mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" -e "SELECT 1" >/dev/null 2>&1; do
        sleep 2
    done
fi

php artisan migrate --seed --force
php artisan sync:jsonplaceholder

exec php artisan serve --host=0.0.0.0 --port=8000
