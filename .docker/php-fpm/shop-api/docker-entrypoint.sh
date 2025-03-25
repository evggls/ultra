#!/bin/sh

set -e

if [ ! -f .env ]; then
  echo "⚙️  Copying .env.example to .env"
  cp .env.example .env
fi

if [ ! -f config/jwt/private.pem ]; then
  echo "🔐 Generating JWT keys..."
  mkdir -p config/jwt
  openssl genrsa -out config/jwt/private.pem -aes256 -passout pass:$JWT_PASSPHRASE 4096
  openssl rsa -pubout -in config/jwt/private.pem -passin pass:$JWT_PASSPHRASE -out config/jwt/public.pem
fi

echo "🛠 Composer install:"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "🛠 Apply migrations:"
php bin/console doctrine:migrations:migrate --no-interaction

exec "$@"