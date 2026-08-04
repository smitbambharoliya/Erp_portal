FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN [ -f .env ] || touch .env

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN cp /app/entrypoint.sh /entrypoint.sh && chmod +x /entrypoint.sh

EXPOSE 8080

CMD ["/entrypoint.sh"]
