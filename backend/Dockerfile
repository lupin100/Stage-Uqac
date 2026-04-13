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

WORKDIR /var/www/html
COPY . .

# Installation des dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# On expose le port et on lance le serveur via Symfony CLI
EXPOSE 8001

#CMD ["php", "-S", "0.0.0.0:8001", "-t", "public"]

# On lance les migrations et les fixtures avant de démarrer le serveur
CMD ["sh", "-c", "php bin/console doctrine:migrations:migrate --no-interaction && php bin/console doctrine:fixtures:load --no-interaction && php -S 0.0.0.0:8001 -t public"]