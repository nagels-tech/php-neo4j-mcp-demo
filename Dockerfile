ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-cli
RUN apt-get update \
    && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    wget \
    && docker-php-ext-install -j$(nproc) bcmath sockets pcntl \
    && wget https://codeclimate.com/downloads/test-reporter/test-reporter-latest-linux-amd64 \
    && mv test-reporter-latest-linux-amd64 /usr/bin/cc-test-reporter  \
    && chmod +x /usr/bin/cc-test-reporter \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /opt/project
