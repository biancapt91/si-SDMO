#!/bin/bash
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Seeding admin user..."
php artisan db:seed --class=AdminUserSeeder --force

echo "Optimizing application..."
php artisan optimize

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=$PORT
