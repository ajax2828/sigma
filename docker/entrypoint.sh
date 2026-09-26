#!/bin/sh
set -e

cd /var/www

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
mkdir -p /tmp/nginx-client-body
chown -R www:www /tmp/nginx-client-body
chmod 775 /tmp/nginx-client-body
mkdir -p /var/www/public/images/members /var/www/public/images/hero /var/www/public/images/backgrounds
chown -R www-data:www-data /var/www/public/images
chmod -R 775 /var/www/public/images

if [ -f /var/www/.env ]; then
    chown www-data:www-data /var/www/.env
fi

if ! grep -q "^APP_KEY=base64:" /var/www/.env 2>/dev/null; then
    echo "Generating APP_KEY..."
    php artisan key:generate --ansi --no-interaction 2>/dev/null || true
fi

chown www-data:www-data /var/www/database
chmod 775 /var/www/database

if [ ! -f "database/database.sqlite" ]; then
    echo "Creating database..."
    touch database/database.sqlite
fi
chown www-data:www-data database/database.sqlite
# The database file is bind-mounted from the host, so its owner may still be
# root inside the container. Keep the file writable for PHP-FPM (www-data).
chmod 666 database/database.sqlite

echo "Running migrations..."
php artisan migrate --force --no-interaction 2>/dev/null || echo "Migration failed, but continuing..."

echo "Seeding admin user..."
php artisan db:seed --force --no-interaction 2>/dev/null || echo "Seeding failed, but continuing..."

echo "Clearing caches..."
rm -f /var/www/bootstrap/cache/*.php 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
