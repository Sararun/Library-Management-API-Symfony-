FROM unit:1.33.0-php8.3

ARG UID=1000
ARG GID=1000
ENV UID=${UID}
ENV GID=${GID}

# Create non root user
RUN addgroup --gid $GID symfony \
    && adduser --uid $UID --gid $GID --shell /bin/bash --home /home/symfony/ symfony

#usefull for dev
COPY ./sources.list /etc/apt/sources.list

# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    vim \
    autoconf \
    curl \
    git \
    unzip \
    wget \
    zip \
    g++ \
    gnupg \
    locales \
    build-essential \
    jpegoptim optipng pngquant gifsicle \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
	libpng-dev \
    libwebp-dev \
    libonig-dev \
	libzip-dev \
    libxpm-dev \
    libmcrypt-dev \
    libpq-dev \
    supervisor \
    htop \
    && apt clean  \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* /var/www/symfony/* \
    && ln -sf /dev/stdout /var/log/unit.log

# Install extensions
RUN docker-php-ext-install zip exif pcntl opcache sockets \
	&& docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

#RUN pecl install uopz && \
#    docker-php-ext-enable uopz

# ---- COMPOSER ----
COPY --from=composer:2.8.5 /usr/bin/composer /usr/local/bin/composer

# Create work directory
WORKDIR /var/www/symfony
RUN chown -R symfony:symfony /var/www/symfony && chmod -R 777 /var/www/symfony

# PHP configuration
COPY ./php/*.ini /usr/local/etc/php/conf.d/

ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Supervisor configuration
COPY ./supervisor/config.conf /etc/supervisor/supervisord.conf
COPY ./supervisor/supervisor.sh /docker-entrypoint.d/supervisor.sh

RUN chmod +x /docker-entrypoint.d/supervisor.sh

EXPOSE 9003
