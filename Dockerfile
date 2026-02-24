FROM php:8.2-fpm-bullseye

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libicu-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip gd intl

# Set working directory
WORKDIR /var/www/html

# Copy source code
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose the port Render uses
ENV PORT=10000
EXPOSE 10000

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]