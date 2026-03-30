#!/usr/bin/env sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

set_env() {
    key="$1"
    value="$2"

    if [ -z "$value" ]; then
        return 0
    fi

    escaped_value=$(printf '%s' "$value" | sed 's/[\/&]/\\&/g')

    if grep -q "^${key}=" .env; then
        sed -i "s/^${key}=.*/${key}=${escaped_value}/" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

set_env "DB_CONNECTION" "${DB_CONNECTION}"
set_env "DB_HOST" "${DB_HOST}"
set_env "DB_PORT" "${DB_PORT}"
set_env "DB_DATABASE" "${DB_DATABASE}"
set_env "DB_USERNAME" "${DB_USERNAME}"
set_env "DB_PASSWORD" "${DB_PASSWORD}"

php artisan key:generate --force
php artisan config:clear

if [ "${DB_CONNECTION}" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
    until mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" -e "SELECT 1" >/dev/null 2>&1; do
        sleep 2
    done
fi

php artisan migrate --seed --force
php artisan sync:jsonplaceholder

exec php artisan serve --host=0.0.0.0 --port=8000
