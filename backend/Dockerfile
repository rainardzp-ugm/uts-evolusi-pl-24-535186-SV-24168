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
    && chown -R www-data:www-data storage bootstrap/cache \
    && tr -d '\r' < docker-entrypoint.sh > /tmp/docker-entrypoint \
    && mv /tmp/docker-entrypoint docker-entrypoint.sh \
    && chmod +x docker-entrypoint.sh

RUN cp docker-entrypoint.sh /usr/local/bin/docker-entrypoint

EXPOSE 80

HEALTHCHECK --interval=10s --timeout=5s --start-period=10s --retries=3 \
    CMD wget -q -O /dev/null http://127.0.0.1/ || exit 1

ENTRYPOINT ["sh", "/usr/local/bin/docker-entrypoint"]
CMD ["frankenphp", "php-server", "--root", "public/"]