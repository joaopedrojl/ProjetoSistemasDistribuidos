# Dockerfile
FROM php:8.2-fpm

# Atualiza pacotes, instala dependências e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libzip-dev \
    unzip \
    git \
    curl \
    ca-certificates \
    openssl \
    libonig-dev \
    && docker-php-ext-install mysqli sockets zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Define o diretório de trabalho
WORKDIR /app
