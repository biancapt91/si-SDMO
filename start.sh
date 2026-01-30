#!/bin/bash
set -e

echo "Starting Laravel application..."
echo "Environment: $APP_ENV"
echo "Port: $PORT"

# Skip migrations if DB not ready
if [ -n "$DB_HOST" ]; then
    echo "Running migrations..."
    php artisan migrate --force || echo "Migration skipped"
    
    echo "Seeding admin user..."
    php artisan db:seed --class=AdminUserSeeder --force || echo "Seeding skipped"
fi

echo "Starting server on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
