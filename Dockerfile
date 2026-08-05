# Stage 1: Build frontend assets
FROM node:20 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP app
FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nginx \
    libpq-dev libzip-dev

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

RUN sed -i 's/listen = 9000/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/zz-docker.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Copy built frontend assets from stage 1
COPY --from=node_builder /app/public/build /var/www/public/build

RUN composer install --optimize-autoloader --no-dev --no-interaction

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]