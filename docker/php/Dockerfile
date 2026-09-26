FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx supervisor sqlite sqlite-dev \
    libzip-dev libpng-dev libjpeg-turbo-dev \
    freetype-dev oniguruma-dev libxml2-dev \
    icu-dev zip git unzip curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql pdo_sqlite mbstring exif pcntl \
        bcmath gd xml zip intl opcache

# Install Composer manually
RUN curl -sS https://getcomposer.org/installer | php -- --2 --install-dir=/usr/local/bin --filename=composer

COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-autoloader --no-scripts --optimize-autoloader

COPY . .
RUN composer dump-autoload --optimize --no-dev

RUN addgroup -g 1000 -S www && adduser -u 1000 -S www -G www
RUN chown -R www:www /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

RUN touch /var/www/database/database.sqlite \
    && chown www:www /var/www/database/database.sqlite

USER root
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
