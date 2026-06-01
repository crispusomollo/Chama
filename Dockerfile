FROM serversideup/php:8.3-fpm-nginx

# 1. Install system dependencies required for PostgreSQL, Zip, and GD
USER root
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Set the working directory to the standard web serving root
WORKDIR /var/www/html

# 3. Copy application files into the container
COPY --chown=www-data:www-data . .

# 4. Switch to the standard web user for security and file permission execution
USER www-data

# 5. Install production PHP composer dependencies cleanly
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 6. Clear out any local machine cache residues
RUN php artisan config:clear || true \
    && php artisan route:clear || true \
    && php artisan cache:clear || true \
    && php artisan view:clear || true

# 7. Set the container entrypoint execution loop
# This runs migrations and database seeding safely right before launching the real Nginx web server
#CMD php artisan migrate --force && \
#    php artisan db:seed --force && \
#    echo "🚀 Database ready. Starting web server..." && \
#    /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
# Start app (Using serversideup's native boot stage logic)
CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    echo "🚀 Database ready. Starting Nginx Web Server..." && \
    exec /usr/local/bin/web-bootstage
