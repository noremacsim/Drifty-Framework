FROM php:7.3-apache

MAINTAINER noremacsim

# Set working directory
WORKDIR /var/www/html/

# Install dependencies
RUN apt-get update
RUN apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libwebp-dev \
    libfreetype6-dev \
    locales \
    libzip-dev\
    imagemagick\
    libmagickwand-dev\
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    xvfb \
    libfontconfig \
    wkhtmltopdf

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install zip exif pcntl
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli


RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir

RUN docker-php-ext-install gd
RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN a2enmod rewrite

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

# Change current user to www-data
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
USER www-data

# Copy existing application directory contents
COPY ./ /var/www/html/
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html/

# RUN composer install