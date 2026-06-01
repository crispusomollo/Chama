FROM php:8.3-apache

# 1. Install production system dependencies for Postgres, Zip, and GD
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    zip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache URL Mod_Rewrite (Mandatory for Laravel routing links)
RUN a2enmod rewrite

# 3. Change Apache's Document Root to point to Laravel's "public" folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Install Composer cleanly
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Set working directory and copy application files
WORKDIR /var/www/html
COPY . .

# 6. Install PHP packages for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. Configure storage permissions so Apache can read/write data profiles
RUN chown -r www-data:www-data /var/www/html \
    && chmod -r 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Clear all optimization caches compiled from your local machine
RUN php artisan config:clear || true \
    && php artisan route:clear || true \
    && php artisan cache:clear || true \
    && php artisan view:clear || true

# 9. Expose Apache's default port
EXPOSE 80

# 10. Execute database upgrades and fire up Apache in the foreground
CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    echo "🚀 Schema ready. Launching Apache..." && \
    apache2-foreground
