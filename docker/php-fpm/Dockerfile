FROM alpine:edge

LABEL maintainer="Vincent Composieux <vincent.composieux@gmail.com>"

RUN apk update
RUN apk upgrade

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Extensions
RUN apk add --update --no-cache coreutils curl git gcc make openrc \
    php7-fpm php7-apcu php7-pecl-amqp php7-ctype php7-curl php7-dom php7-gd php7-iconv php7-imagick \
    php7-json php7-intl php7-pecl-mcrypt php7-fileinfo php7-mbstring php7-opcache php7-openssl \
    php7-pdo php7-pdo_mysql php7-mysqli php7-xml php7-zlib php7-phar php7-tokenizer php7-pear php7-dev\
    php7-session php7-simplexml php7-pecl-xdebug php7-zip php7-xmlwriter php7-gmp rabbitmq-c \
    rabbitmq-c-dev sudo supervisor

RUN composer global require friendsofphp/php-cs-fixer

ENV PATH "$PATH:/root/.composer/vendor/bin"

RUN mkdir /var/run/openrc
RUN touch /var/run/openrc/softlevel
RUN touch /var/run/supervisor.sock

COPY php.ini /etc/php7/conf.d/
COPY symfony.ini /etc/php7/conf.d/
COPY symfony.ini /etc/php7/cli/conf.d/
COPY xdebug.ini  /etc/php7/conf.d/
COPY supervisord.conf /etc/supervisor/conf.d/
COPY symfony.pool.conf /etc/php7/php-fpm.d/

RUN supervisord -c /etc/supervisor/conf.d/supervisord.conf
RUN supervisorctl -c /etc/supervisor/conf.d/supervisord.conf

CMD ["php-fpm7", "-F"]

WORKDIR /opt/app-root/src

EXPOSE 9000
EXPOSE 9001

RUN php -v
