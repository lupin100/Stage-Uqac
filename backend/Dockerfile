# On part de la version exacte demandée
FROM php:8.5.1-cli

# Installation des dépendances système pour PostgreSQL et Symfony CLI
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo pdo_pgsql \
        zip \
        intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# Installation de Composer 2.9.5
COPY --from=composer:2.9.5 /usr/bin/composer /usr/bin/composer

# Installation de Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/bin/symfony

WORKDIR /var/www/html
COPY . .

# Installation des dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader


# On expose le port et on lance le serveur via Symfony CLI
EXPOSE 8001
CMD ["symfony", "server:start", "--listen-ip=0.0.0.0", "--port=8001", "--no-tls"]