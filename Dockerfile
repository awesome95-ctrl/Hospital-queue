FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd intl


RUN a2dismod mpm_event mpm_worker mpm_prefork || true \
    && a2enmod mpm_prefork rewrite

RUN echo "MY NEW DOCKERFILE IS RUNNING"


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

# RUN php artisan config:clear

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

EXPOSE 80

CMD ["apache2-foreground"]