FROM php:8.2-cli-bullseye

# Cài thư viện hệ thống
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip gd intl

WORKDIR /var/www/html

# Copy code
COPY . .

# Cài composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Quyền thư mục
RUN chown -R www-data:www-data storage bootstrap/cache

# Render dùng biến PORT
ENV PORT=10000
EXPOSE 10000

# (Tuỳ chọn) Nếu DB ok, chạy migrate luôn:
# CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT}

# Đơn giản nhất: chỉ chạy server Laravel
CMD php artisan serve --host=0.0.0.0 --port=${PORT}