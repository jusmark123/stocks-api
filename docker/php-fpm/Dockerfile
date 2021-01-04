FROM alpine:edge

LABEL maintainer="Vincent Composieux <vincent.composieux@gmail.com>"

RUN apk add --update --no-cache \
    coreutils \
    php7-fpm \
    php7-pecl-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-pecl-imagick \
    php7-json \
    php7-intl \
    php7-pecl-mcrypt \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-pecl-xdebug \
    php7-zip \
    php7-xmlwriter \
    php7-gmp \
    git \
    make \
    curl \
    openrc \
    sudo \
    supervisor \
    openssl \
    acl
RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

RUN composer self-update
RUN composer global require friendsofphp/php-cs-fixer
ENV PATH "$PATH:/root/.composer/vendor/bin"

RUN mkdir /var/run/openrc
RUN touch /var/run/openrc/softlevel
RUN touch /var/run/supervisor.sock
RUN chmod 777 /var/run/supervisor.sock

COPY symfony.ini /etc/php7/conf.d/
COPY symfony.ini /etc/php7/cli/conf.d/
COPY xdebug.ini  /etc/php7/conf.d/
COPY supervisord.conf /etc/supervisor/conf.d/
COPY symfony.pool.conf /etc/php7/php-fpm.d/

RUN supervisord -c /etc/supervisor/conf.d/supervisord.conf

RUN if [[ -d /opt/app-root/src/config/jwt ]] ; then rm -R /opt/app-root/src/config/jwt ; fi
RUN mkdir -p /opt/app-root/src/config/jwt
ARG JWT_PASSPHRASE
ENV JWT_PASSPHRASE "$JWT_PASSPHRASE"
RUN echo $JWT_PASSPHRASE | openssl genpkey -out /opt/app-root/src/config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
RUN echo $JWT_PASSPHRASE | openssl pkey -in /opt/app-root/src/config/jwt/private.pem -passin stdin -out /opt/app-root/src/config/jwt/public.pem -pubout
RUN setfacl -R -m u:nobody:rX -m u:"$(whoami)":rwX /opt/app-root/src/config/jwt
RUN setfacl -dR -m u:nobody:rX -m u:"$(whoami)":rwX /opt/app-root/src/config/jwt

CMD ["php-fpm7", "-F"]

WORKDIR /opt/app-root/src
EXPOSE 9000
EXPOSE 9001
