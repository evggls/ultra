#!/bin/sh

set -e

echo "🛠 Composer install:"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "🛠 Apply migrations:"
php bin/console doctrine:migrations:migrate --no-interaction

exec php-fpm