FROM php:8.2-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql bcmath

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN php artisan config:clear

# Railway needs this metadata
EXPOSE 8080

# Single PID HTTP server (Railway-safe)
CMD php -S 0.0.0.0:${PORT} -t public

RUN mkdir -p database \
 && touch database/database.sqlite
