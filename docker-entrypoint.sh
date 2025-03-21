#!/bin/sh
set -e

# Step 1: Copy .env.example to .env if .env doesn't exist
if [ ! -f .env ]; then
  echo "No .env found. Copying .env.example to .env..."
  cp .env.example .env

  # Step 2: Generate the app key
  echo "Generating APP_KEY..."
  php artisan key:generate
else
  echo ".env already exists. Skipping creation and key generation."
fi

# Step 3: Create, migrate and seed the database.
echo "Creating, migrating and seeing the database..."
if [ ! -f database/database.sqlite ]; then
  echo "No database.sqlite file found, creating one..."
  touch database/database.sqlite
else
  echo "database.sqlite file already exists, skipping."
fi
php artisan migrate:fresh --seed

# Step 4: Start the Laravel development server
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000
