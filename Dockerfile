FROM php:7.4-fpm-alpine

# install extensions
# intl, zip, soap
RUN apk add --update --no-cache libintl icu icu-dev libxml2-dev libzip libzip-dev \
    && docker-php-ext-install intl zip soap

# mysqli, pdo, pdo_mysql, pdo_pgsql
RUN apk add --update --no-cache postgresql-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

# mcrypt, gd, iconv
RUN apk add --update --no-cache \
        freetype-dev \
        php7-dev \
        libc-dev \
        gcc \
        make \
        cmake \
        libjpeg-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && pecl install mcrypt \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install -j"$(getconf _NPROCESSORS_ONLN)" iconv \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j"$(getconf _NPROCESSORS_ONLN)" gd

# gmp
RUN apk add --update --no-cache gmp gmp-dev \
    && docker-php-ext-install gmp

# php-redis
ENV PHPREDIS_VERSION 3.1.2

RUN docker-php-source extract \
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && docker-php-ext-install redis \
    && docker-php-source delete

# apcu
RUN docker-php-source extract \
    && apk add --no-cache --virtual .phpize-deps-configure $PHPIZE_DEPS \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apk del .phpize-deps-configure \
    && docker-php-source delete


# git client
RUN apk add --update --no-cache git nodejs npm oniguruma oniguruma-dev

# imagick
RUN apk add --update --no-cache autoconf g++ imagemagick-dev libtool make pcre-dev icu-dev gettext-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# install bcmath extension
RUN docker-php-ext-install bcmath
# Change TimeZone
RUN echo "Set default timezone - Europe/Vienna"
RUN echo "Europe/Vienna" > /etc/timezone

# Install composer globally
RUN echo "Install composer globally"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-install -j$(nproc) exif pcntl bcmath gd intl mysqli pdo_mysql shmop opcache gettext sockets sysvmsg sysvsem sysvshm tokenizer
#RUN docker-php-ext-install curl ftp

# failed to install
RUN docker-php-ext-install posix phar readline

# already loaded
RUN docker-php-ext-install pdo mbstring dom iconv json

# Installs latest Chromium (85) package.
RUN apk add --no-cache \
      chromium \
      nss \
      freetype \
      freetype-dev \
      harfbuzz \
      ca-certificates \
      ttf-freefont \
      nodejs \
      yarn \
      nginx


# Tell Puppeteer to skip installing Chrome. We'll be using the installed package.
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser

# Puppeteer v5.2.1 works with Chromium 85.
RUN yarn add puppeteer@5.2.1

RUN sed -i 's/memory_limit = 128M/memory_limit = 2048M/g' /etc/php7/php.ini
RUN sed -i 's/max_execution_time = 30/max_execution_time = 3000/g' /etc/php7/php.ini

WORKDIR /var/www

ADD . /var/www/

COPY ./.env.example /var/www/.env

COPY ./storage /opt/storage

COPY ./docker/odin.conf /etc/nginx/conf.d/default.conf

RUN mkdir /run/nginx && chown nginx:root /run/nginx

RUN chown -R nginx:nginx /var/www

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

USER nginx

RUN composer install

RUN npm install

RUN npx browserslist@latest --update-db

RUN composer require spatie/browsershot

RUN npm i pixelmatch

RUN npm run prod

EXPOSE 80

ENTRYPOINT /var/www/docker/entrypoint.sh

