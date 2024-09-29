#!/bin/bash

set -e

# Instalar as dependências via Composer, se a pasta "vendor" não existir
if [ ! -d "vendor" ]; then
    composer install
fi

# Copiar o arquivo .env se ele não existir e gerar uma nova chave
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Executar migrações se necessário (opcional)
# php artisan migrate --force

# Iniciar o servidor Laravel diretamente
php artisan serve --host=0.0.0.0 --port=8000
