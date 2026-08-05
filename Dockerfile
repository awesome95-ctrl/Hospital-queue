# Production Dockerfile for Laravel 12 on Render
# Builds frontend assets, installs PHP dependencies, and serves public/ through Nginx.

# Stage 1: Build Vite assets
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci --silent
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
RUN npm run build

# Stage 2: Install PHP dependencies and prepare application
FROM php:8.2-fpm AS vendor
WORKDIR /var/www/html

# Install required build-time dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        unzip \
        libzip-dev \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libxml2-dev \
        libicu-dev \
        libonig-dev \
        libcurl4-openssl-dev \
        pkg-config \
        gettext-base \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        pdo_mysql \
        xml \
        zip \
    # && pecl install apcu \
    # && docker-php-ext-enable apcu \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --prefer-dist --no-interaction

# Copy application source and build assets
COPY . ./
COPY --from=assets /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Stage 3: Production image with Nginx
FROM vendor AS production

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        curl \
        ca-certificates \
    && apt-get purge -y --auto-remove \
        libzip-dev \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libxml2-dev \
        libicu-dev \
        libonig-dev \
        libcurl4-openssl-dev \
        pkg-config \
        gettext-base \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/cache/apt/*

RUN mkdir -p /run/nginx

RUN printf '%s\n' \
    'server {' \
    '    listen {{PORT}} default_server;' \
    '    listen [::]:{{PORT}} default_server;' \
    '    server_name _;' \
    '    root /var/www/html/public;' \
    '    index index.php index.html;' \
    '    charset utf-8;' \
    '    client_max_body_size 100M;' \
    '' \
    '    location / {' \
    '        try_files $uri $uri/ /index.php?$query_string;' \
    '    }' \
    '' \
    '    location = /favicon.ico { access_log off; log_not_found off; }' \
    '    location = /robots.txt  { access_log off; log_not_found off; }' \
    '' \
    '    location ~* \.(js|css|png|jpg|jpeg|gif|svg|webp|ico|ttf|woff|woff2|eot)$ {' \
    '        expires 1y;' \
    '        access_log off;' \
    '        add_header Cache-Control "public, immutable";' \
    '    }' \
    '' \
    '    location ~ \.php$ {' \
    '        fastcgi_pass unix:/var/run/php/php-fpm.sock;' \
    '        fastcgi_index index.php;' \
    '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' \
    '        include fastcgi_params;' \
    '    }' \
    '' \
    '    location ~ /\. {' \
    '        deny all;' \
    '    }' \
    '}' > /etc/nginx/conf.d/default.template

RUN printf '%s\n' '#!/bin/sh' \
    'set -e' \
    'PORT=${PORT:-8080}' \
    'sed "s/{{PORT}}/${PORT}/g" /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf' \
    'php-fpm -R &' \
    'exec nginx -g "daemon off;"' > /usr/local/bin/render-entrypoint.sh \
    && chmod +x /usr/local/bin/render-entrypoint.sh

ENV PORT=8080
EXPOSE 8080
WORKDIR /var/www/html

CMD ["/usr/local/bin/render-entrypoint.sh"]
