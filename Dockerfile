FROM php:7.1-cli-alpine

RUN apk update \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && rm -rf /var/cache/apk/* /var/tmp/*/tmp/*

WORKDIR /package
COPY composer.json /package
COPY composer.lock /package
RUN composer install --no-scripts --no-autoloader --no-dev

COPY . /package
RUN composer dump-autoload

ENTRYPOINT ["tail", "-f", "/dev/null"]
