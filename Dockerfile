# Stage 1: Composer - Install PHP dependencies
FROM composer:2 AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Stage 2: Final PHP Apache image
FROM php:8.2-apache

# Install PostgreSQL extension
RUN apt update && apt install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && apt clean && rm -rf /var/lib/apt/lists/*

# Copy application files
WORKDIR /var/www/html
COPY --from=composer /app/vendor ./vendor
COPY . .

RUN rm composer.json composer.lock

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
