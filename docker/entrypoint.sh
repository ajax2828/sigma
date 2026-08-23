#!/bin/sh
set -e

# Fungsi helper log
info() { echo "[entrypoint] $*"; }

cd /var/www

# 1. .env handling - jika tidak ada .env, copy dari .env.example / .env.docker
if [ ! -f .env ]; then
  if [ -f .env.docker ]; then
    cp .env.docker .env
    info ".env dibuat dari .env.docker"
  elif [ -f .env.example ]; then
    cp .env.example .env
    info ".env dibuat dari .env.example"
  fi
fi

# 2. Pastikan APP_KEY ada
if ! grep -q "APP_KEY=base64" .env 2>/dev/null || grep -q "APP_KEY=$" .env 2>/dev/null || grep -q "APP_KEY= *$" .env 2>/dev/null; then
  if [ -z "$(grep APP_KEY .env | cut -d'=' -f2)" ]; then
    info "Generate APP_KEY..."
    php artisan key:generate --force || true
  fi
fi

# 3. Tunggu DB siap (khusus mysql)
if echo "$DB_CONNECTION" | grep -qi "mysql"; then
  info "Menunggu MySQL $DB_HOST:$DB_PORT ..."
  max=60; i=0
  until php -r "
    try { new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); }
    catch(Exception \$e){ exit(1); }" 2>/dev/null; do
    i=$((i+1))
    if [ $i -ge $max ]; then
      info "DB belum siap setelah ${max}s, lanjut tetap..."
      break
    fi
    sleep 1
  done
  info "DB siap (atau timeout terlampaui)"
fi

# 4. Permission storage & bootstrap
mkdir -p storage/framework/{sessions,views,cache/data} storage/app/public bootstrap/cache public/storage/qr-codes
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# 5. storage:link (idempotent)
if [ ! -L public/storage ]; then
  php artisan storage:link || true
  info "storage:link dibuat"
fi

# 6. Composer install jika vendor belum ada (dev mode)
if [ ! -f vendor/autoload.php ]; then
  info "vendor belum ada, composer install..."
  composer install --no-interaction --prefer-dist --optimize-autoloader || true
fi

# 7. Node build check - jika public/build belum ada dan npm tersedia, build
if [ ! -d public/build ] && [ -f package.json ]; then
  if command -v npm >/dev/null 2>&1; then
    info "public/build belum ada, npm build..."
    npm ci --ignore-scripts || npm install || true
    npm run build || true
  else
    info "public/build belum ada tapi npm tidak tersedia - lewati (dev: gunakan service node/vite)"
  fi
fi

# 8. Optimize & migrate
info "php artisan migrate --force..."
php artisan migrate --force || info "migrate gagal, periksa DB config"

info "cache clear & config cache..."
php artisan config:clear || true
php artisan cache:clear || true
# jangan cache config di local agar .env hot-reload
if [ "$APP_ENV" = "production" ]; then
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

info "Entrypoint selesai, exec $@"
exec "$@"
