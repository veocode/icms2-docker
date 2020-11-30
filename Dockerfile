FROM php:7.2-apache

ENV TERM=xterm

# SYSTEM SETUP
RUN apt-get update -y
RUN apt-get install -y --no-install-recommends apt-utils \
    && apt-get install -y sendmail \
    && apt-get install -y libpng-dev \
    && apt-get install -y libjpeg62-turbo-dev \
    && apt-get install -y libfreetype6-dev \
    && apt-get install -y libxml2-dev \
    && apt-get install -y libssl-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y libmcrypt-dev \
    && apt-get install -y memcached \
    && apt-get install -y libmemcached-dev \
    && apt-get install -y libicu-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y python-certbot-apache \
    && apt-get install -y certbot

# APACHE SETUP
RUN a2enmod rewrite
RUN a2enmod ssl
RUN service apache2 restart

# PHP EXTENSIONS
RUN docker-php-ext-install opcache
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install zip
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

# PHP IONCUBE LOADER
COPY ./vendor/ioncube/ioncube_loader_lin_7.2.so /tmp/ioncube/ioncube_loader_lin_7.2.so
RUN mv /tmp/ioncube/ioncube_loader_lin_7.2.so `php-config --extension-dir`
RUN docker-php-ext-enable ioncube_loader_lin_7.2

# PHP MEMCACHED
COPY ./vendor/memcached /usr/src/php/ext/memcached
RUN docker-php-ext-configure /usr/src/php/ext/memcached --disable-memcached-sasl \
    && docker-php-ext-install /usr/src/php/ext/memcached \
    && rm -rf /usr/src/php/ext/memcached

# CUSTOM CONF DIRECTORY
RUN mkdir /opt/custom.conf

# PHP CONFIGURATION LINKS
RUN mkdir /opt/custom.conf/php
COPY ./services/php/php.ini /opt/custom.conf/php/php.ini
WORKDIR /usr/local/etc/php
RUN ln -s /opt/custom.conf/php/php.ini php.ini

# APACHE CONFIGURATION LINKS
RUN mkdir /opt/custom.conf/apache
COPY ./services/apache/conf/ /opt/custom.conf/apache/
WORKDIR /etc/apache2
RUN rm -f apache2.conf \
    sites-available/000-default.conf \
    sites-available/default-ssl.conf
RUN ln -s /opt/custom.conf/apache/apache2.conf apache2.conf
RUN ln -s /opt/custom.conf/apache/site.conf sites-available/000-default.conf
