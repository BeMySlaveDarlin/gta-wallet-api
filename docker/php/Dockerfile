FROM php:8.1-fpm

LABEL maintainer="Aziz Muzafarov <bemyslavedarlin@gmail.com>"

# Set up environments
ENV DEBIAN_FRONTEND noninteractive
ENV TERM xterm-color

# Update reps
RUN apt-get -y update

# Set up timezone
RUN apt-get install --no-install-recommends -yq tzdata
RUN cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime

# Install libs
RUN apt-get install --no-install-recommends -yq \
    mc nano git wget curl zip unzip htop procps g++ \
    re2c ca-certificates zlib1g-dev inotify-tools supervisor \
    libicu-dev libpq-dev libzip-dev libmemcached-dev libmemcached11

# Install libs
RUN docker-php-ext-install zip
RUN docker-php-ext-install shmop
RUN docker-php-ext-install gettext
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install pdo_mysql pdo_pgsql
RUN docker-php-ext-install sysvmsg sysvsem sysvshm
RUN docker-php-ext-install sockets

# Set up php.ini and configuring it
ENV PHP_INI_PATH=/usr/local/etc/php/php.ini
RUN cp /usr/local/etc/php/php.ini-production ${PHP_INI_PATH} \
    && sed -i -e 's/^default_charset = .*/default_charset = "UTF-8"/g' ${PHP_INI_PATH} \
    && sed -i -e 's/^default_mimetype = .*/default_mimetype = "text\/json"/g' ${PHP_INI_PATH} \
    && sed -i -e 's/^post_max_size = .*/post_max_size = 32M/g' ${PHP_INI_PATH} \
    && sed -i -e 's/^upload_max_filesize = .*/upload_max_filesize = 32M/g' ${PHP_INI_PATH}

# Install composer
ENV PATH /root/composer/vendor/bin:/app/vendor/bin:/app/bin:${PATH}
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer

# Clean
RUN apt-get autoremove -y \
    && apt-get clean -y \
    && rm -rf /tmp/* /var/tmp/* \
    && rm -rf /var/lib/apt/lists/* \
    && find /var/cache -type f -delete \
    && find /var/log -type f | while read f; do echo -n '' > ${f}; done

WORKDIR /app
