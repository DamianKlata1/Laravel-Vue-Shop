FROM php:8.2-apache as shop-php

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        libbz2-dev \
        libc-client-dev \
        libkrb5-dev \
        libcurl4-openssl-dev \
        libicu-dev \
        libgd-dev \
        libxml2-dev \
        libpq-dev \
        libzip-dev \
        nano \
        wget \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN docker-php-ext-install \
        bcmath \
        ctype \
        shmop \
        gd \
        iconv \
        intl \
        pcntl \
        pdo \
        calendar \
        pdo_pgsql \
        pgsql \
        simplexml \
        soap \
        sockets \
        zip \
        opcache \
        sysvsem

RUN pecl install mailparse \
    && docker-php-ext-enable mailparse

# Dodaj --with-kerberos do konfiguracji rozszerzenia imap
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

# Konfiguracja specyficzna dla rozszerzenia gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Instalacja Xdebug
#RUN pecl install xdebug-2.9.8 \
#    && docker-php-ext-enable xdebug

## Konfiguracja Xdebug
#RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.client_port=51346" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.log=/var/www/html/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN a2dismod ssl
RUN a2enmod rewrite
RUN a2enmod headers

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY .docker/apache/000-default.conf /etc/apache2/sites-available/
COPY .docker/php/php.ini /usr/local/etc/php/php.ini



# Enable Apache mod_rewrite
RUN a2dismod ssl
RUN a2enmod rewrite
RUN a2enmod headers

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash && \
      export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")" && \
      [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"


RUN apt install python2 -y


WORKDIR /var/www

COPY . .

