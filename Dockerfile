# Dockerfile na raiz
FROM php:8.2-fpm

# Instala dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev default-mysql-client \
    && docker-php-ext-install mysqli pdo_mysql sockets zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copia o composer.json e composer.lock (se existir)
COPY api/composer.json composer.lock* ./

# Instala o Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Instala dependências do PHP via Composer
RUN composer install --ignore-platform-reqs --no-interaction

# Copia todo o código
COPY api/ ./api/
COPY consumer/ ./consumer/
