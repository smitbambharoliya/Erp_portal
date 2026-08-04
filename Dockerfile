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

# Ensure all possible .env files exist and are readable by all users
RUN touch .env .env.local .env.dev .env.dev.local .env.prod .env.prod.local \
    && chmod 777 .env*

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod
ENV APP_RUNTIME_OPTIONS="disable_dotenv=1"

# Run composer install with --no-scripts to prevent build-time database connection attempts
RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 8080

CMD php bin/console cache:clear && php -S 0.0.0.0:$PORT -t public public/index.php
