#!/bin/sh
echo "Attente de la base de données PostgreSQL..."
until php bin/console dbal:run-sql "SELECT 1" > /dev/null 2>&1; do
sleep 2
done
echo "Base de données prête !"
echo "Lancement des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction
exec "$@"