FROM php:7.1-alpine

RUN apk add $PHPIZE_DEPS \
    && pecl install xdebug

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
COPY tests.sh /usr/local/bin/tests
COPY coverage.sh /usr/local/bin/coverage
COPY shape /usr/local/bin/shape
COPY test-coverage.php /usr/local/bin/test-coverage

ENV CLASS_COVERAGE_MAX 100
ENV CLASS_COVERAGE_MIN 0
ENV METHOD_COVERAGE_MAX 100
ENV METHOD_COVERAGE_MIN 0
ENV LINE_COVERAGE_MAX 100
ENV LINE_COVERAGE_MIN 0

# Setup entrypoint
COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT /entrypoint.sh
