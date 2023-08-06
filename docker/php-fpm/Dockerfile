FROM phpdockerio/php:8.2-fpm
WORKDIR "/application"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php8.2-amqp \
        php8.2-ast \
        php8.2-bcmath \
        php8.2-bz2 \
        php8.2-dba \
        php8.2-decimal \
        php8.2-http \
        php8.2-igbinary \
        php8.2-intl \
        php8.2-mcrypt \
        php8.2-mysql \
        php8.2-raphf \
        php8.2-xdebug \
        php8.2-xml \
        php8.2-xmlrpc \
        php8.2-xsl \
        php8.2-yaml; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
