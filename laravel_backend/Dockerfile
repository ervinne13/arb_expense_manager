FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Notes: make sure to add other dependencies on this one line.
# rebuilds will generally go faster this way as installations
# wont run again when we change other commands.
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g $uid $user
RUN useradd -u $uid -ms /bin/bash -g $user $user

COPY --chown=$user:$user . /var/www/laravel
RUN chmod -R ug+w /var/www/laravel/storage

WORKDIR /var/www/laravel

USER $user

RUN echo "alias ll='ls -alF'" >> ~/.bashrc
# RUN composer install --optimize-autoloader --no-dev
 # I'm having trouble with composer 2.x and i can't do --no-dev, for now, let's go with this:
RUN composer install
RUN composer dump-autoload
RUN php artisan key:generate

RUN npm i
# RUN npm audit fix # This is preferred but let's disable for now, it's currently really slow

EXPOSE 80
EXPOSE 8000