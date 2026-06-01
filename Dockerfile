FROM php:8.3-apache

# 1. Install system dependencies for Postgres, Zip, and GD
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

# 2. Enable Apache URL Mod_Rewrite
RUN a2enmod rewrite

# 3. Change Apache's Document Root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Inject public directory overrides to resolve Symfony 500 error
RUN cat << 'EOF' >> /etc/apache2/apache2.conf
<Directory /var/www/html/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
EOF

# 5. Map Apache to listen on Render's hidden port 10000
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:10000>/g' /etc/apache2/sites-available/*.conf

# 6. Install Composer dependency manager
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 7. Set core working directories
WORKDIR /var/www/html
COPY . .

# 8. Pull production PHP assets cleanly
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Configure absolute read and write folder permissions recursively
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Clean out static configurations generated on your local computer
RUN rm -f bootstrap/cache/config.php \
    && rm -f bootstrap/cache/routes.php \
    && rm -f bootstrap/cache/views.php

# 11. Declare operational exposed port parameters
EXPOSE 10000

# 12. Set up writable memory directories, verify schema, and launch Apache worker loops
CMD export VIEW_COMPILED_PATH=/tmp/storage/framework/views && \
    mkdir -p /tmp/storage/framework/views /tmp/storage/framework/cache /tmp/storage/framework/sessions && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:clear && \
    php artisan migrate --force && \
    echo "🚀 Dynamic configurations active. Launching web container instance..." && \
    apache2-foreground

