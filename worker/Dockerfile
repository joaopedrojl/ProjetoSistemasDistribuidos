# worker/Dockerfile
FROM php:8.2-cli

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
        unzip \
        git \
        libzip-dev \
    && docker-php-ext-install sockets bcmath zip

# Copiar código
WORKDIR /worker
COPY . /worker

# Instalar Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && mv composer.phar /usr/local/bin/composer

# Instalar dependências do projeto
RUN composer install

CMD ["php", "/worker/consumer.php"]
