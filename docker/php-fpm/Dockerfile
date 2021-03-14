FROM php:7.4-fpm-alpine

RUN apk update
RUN apk upgrade

WORKDIR /opt/app-root/src

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Extensions
RUN apk add --update --no-cache ${PHPIZE_DEPS} \
    bash \
    bash-doc \
    bash-completion \
    ca-certificates \
    coreutils \
    curl \
    git \
    gcc \
    make \
    openrc \
    openssh-client \
    openssl \
    sudo \
    supervisor \
 && pecl install apcu-5.1.18 \
 && docker-php-ext-enable apcu \
 && pecl install xdebug-3.0.2 \
 && docker-php-ext-enable xdebug \
 && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pcntl \
    sockets \
 && docker-php-ext-enable \
    pdo \
    pdo_mysql \
    pcntl \
    sockets

RUN composer global require friendsofphp/php-cs-fixer
RUN curl -sS https://get.symfony.com/cli/installer | bash

ENV PATH "$PATH:/root/.symfony/bin:$PATH"
ENV PATH "$PATH:/root/.composer/vendor/bin:$PATH"

RUN mkdir /var/run/openrc
RUN touch /var/run/openrc/softlevel
RUN touch /var/run/supervisor.sock

COPY php.ini            /usr/local/etc/php/conf.d/
COPY symfony.ini        /usr/local/etc/php/conf.d/
COPY symfony.ini        /usr/local/etc/php/cli/conf.d/
COPY xdebug.ini         /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY symfony.pool.conf  /usr/local/etc/php/php-fpm.d/

COPY supervisord.conf   /etc/supervisor/conf.d/
RUN supervisord -c      /etc/supervisor/conf.d/supervisord.conf
RUN supervisorctl -c    /etc/supervisor/conf.d/supervisord.conf

CMD ["php-fpm", "-F"]

EXPOSE 9000

RUN php -v