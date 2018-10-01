FROM php:7.1-alpine

RUN apk add $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN apk add git
RUN apk add bash
RUN rm -rf /var/cache/apk/* /var/tmp/*/tmp/*

WORKDIR /package
RUN git init \
    && git config --global user.name "package" \
    && git config --global user.email "package@mvfglobal.com"

COPY version.sh /scripts/version.sh
COPY build.sh /scripts/build.sh
COPY bin/patch /usr/local/bin/patch
COPY bin/minor /usr/local/bin/minor
COPY bin/major /usr/local/bin/major

# Setup entrypoint
COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT /entrypoint.sh
