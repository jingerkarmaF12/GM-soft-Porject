FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Create Laravel required directories
RUN mkdir -p bootstrap/cache storage/framework/cache \
    storage/framework/sessions storage/framework/views

# Give permissions
RUN chmod -R 775 storage bootstrap/cache

RUN composer install

EXPOSE 8001

CMD ["sh", "-c", "until php artisan migrate --force; do echo 'Waiting for MySQL...'; sleep 3; done; php artisan serve --host=0.0.0.0 --port=8000"]