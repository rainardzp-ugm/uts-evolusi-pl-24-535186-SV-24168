FROM dunglas/frankenphp:1-php8.3-alpine

# Install ekstensi pendukung Laravel
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql gd zip

# Copy composer dari official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer manifest untuk optimasi layer cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy seluruh kode backend
COPY . .

# Autoload optimization & set permission
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

# Jalankan Laravel langsung menggunakan server bawaan FrankenPHP
CMD ["frankenphp", "php-server", "--root", "public/"]