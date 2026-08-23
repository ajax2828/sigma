# syntax=docker/dockerfile:1.6
# SPM - Laravel 11 + PHP 8.2 + Vite
# Build: docker compose build
# Run:   docker compose up -d

# ── Stage 1: composer vendor ──
FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# Install tanpa script agar tidak butuh .env / DB saat build
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

# ── Stage 2: frontend build (vite) ──
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* yarn.lock* pnpm-lock.yaml* ./
# npm ci jika lock ada, fallback ke npm install
RUN if [ -f package-lock.json ]; then npm ci; \
    elif [ -f yarn.lock ]; then yarn install --frozen-lockfile; \
    else npm install; fi
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
# Build akan menghasilkan public/build (manifest.json)
RUN npm run build || (echo "vite build gagal, cek vite.config.js" && exit 1)

# ── Stage 3: runtime php-fpm ──
FROM php:8.2-fpm-alpine AS app

# System deps untuk ekstensi PHP
RUN apk add --no-cache \
    bash fcgi icu-dev libzip-dev oniguruma-dev \
    freetype-dev libjpeg-turbo-dev libpng-dev \
    libxml2-dev curl-dev \
    $PHPIZE_DEPS

# Ekstensi PHP yang dibutuhkan Laravel + maatwebsite/excel + simple-qrcode
# gd butuh freetype/jpeg, zip butuh libzip, intl butuh icu
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    bcmath \
    exif \
    pcntl \
    zip \
    gd \
    intl \
    opcache \
 && docker-php-ext-enable opcache \
 && apk del $PHPIZE_DEPS \
 && rm -rf /tmp/* /var/cache/apk/*

# Composer binary dari stage vendor
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www

# Copy composer files + install vendor (reuse stage1 cache)
COPY composer.json composer.lock ./
COPY --from=vendor /app/vendor ./vendor
# Generate autoloader + run scripts sekarang (sudah ada kode)
COPY . .
RUN composer dump-autoload --optimize --no-dev \
 && composer run-script post-autoload-dump || true

# Copy hasil vite build (timpa public/build dari host)
COPY --from=frontend /app/public/build ./public/build

# PHP ini overrides
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Permission
RUN mkdir -p storage/framework/{sessions,views,cache/data} storage/app/public bootstrap/cache public/storage/qr-codes \
 && chown -R www-data:www-data storage bootstrap/cache public \
 && chmod -R 775 storage bootstrap/cache

# Healthcheck untuk php-fpm
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
