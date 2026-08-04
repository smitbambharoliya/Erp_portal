FROM php:8.2-cli

# Install required system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip \
    && rm -rf /var/lib/apt/lists/*

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Ensure .env exists and is readable
RUN [ -f .env ] || echo "# fallback" > .env && chmod 644 .env

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod

# Run composer install
RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 8080

CMD php bin/console cache:clear && php -S 0.0.0.0:$PORT -t public public/index.php
