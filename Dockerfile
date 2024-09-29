FROM php:8.1-fpm

# Atualiza os pacotes e instala dependências
RUN apt-get update && apt-get install -y \
    libpq-dev \
    cron \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o código da aplicação
COPY . /var/www/html

# Ajusta as permissões da aplicação
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copia o entrypoint.sh para o local correto
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Iniciar cron e PHP-FPM no entrypoint
ENTRYPOINT ["entrypoint.sh"]

# Expor a porta do PHP-FPM (se necessário)
EXPOSE 9000
