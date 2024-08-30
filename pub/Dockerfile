FROM php:8-fpm-alpine

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.* /var/www/html
COPY *.php /var/www/html

RUN composer install --prefer-dist --no-dev --optimize-autoloader

COPY . .

#CMD ["php", "-S", "0.0.0.0:8900", "-t", "/var/www/html"]