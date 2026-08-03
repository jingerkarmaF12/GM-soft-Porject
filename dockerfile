FROM php:8.1-fpm

# System deps
RUN apt-get update && apt-get install -y \
	git \
	curl \
	zip \
	unzip \
	libpng-dev \
	libonig-dev \
	libxml2-dev \
	libzip-dev \
	libpq-dev \
	&& docker-php-ext-install pdo pdo_mysql zip bcmath sockets xml gd

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev || true

# Copy application
COPY . /var/www/html

# Ensure storage and bootstrap cache permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 9000

CMD ["php-fpm"]
