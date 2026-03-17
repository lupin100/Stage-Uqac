# On part de la version exacte demandée
FROM php:8.5.1-cli

# Installation des dépendances système pour PostgreSQL et Symfony CLI
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# Installation de Composer 2.9.5
COPY --from=composer:2.9.5 /usr/bin/composer /usr/bin/composer

# Installation de Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/bin/symfony

WORKDIR /var/www/html
COPY . .

# On expose le port et on lance le serveur via Symfony CLI
EXPOSE 8000
CMD ["symfony", "server:start", "--port=8000", "--no-tls"]