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
#ENV APACHE_DOCUMENT_ROOT /var/www/html/public
#RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
#RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ADD THIS CRITICAL SYMFONY/REWRITE OVERRIDE BLOCK:
#RUN echo '<Directory /var/www/html/public>\n\
#    Options Indexes FollowSymLinks\n\
#    AllowOverride All\n\
#    Require all granted\n\
#</Directory>' >> /etc/apache2/apache2.conf

# 3. Change Apache's Document Root to point to Laravel's "public" folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Clean multi-line rewrite injection to fix the Symfony 2307 roadblock
RUN cat << 'EOF' >> /etc/apache2/apache2.conf
<Directory /var/www/html/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
EOF



# 4. CRITICAL FOR RENDER FREE TIER: Force Apache to run on port 10000
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:10000>/g' /etc/apache2/sites-available/*.conf

# 5. Install Composer cleanly
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6. Set working directory and copy application files
WORKDIR /var/www/html
COPY . .

# 7. Install PHP packages for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8. Fixed Capitalization: Configure storage permissions recursively (-R)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Clear all optimization caches compiled from your local machine
#RUN php artisan config:clear || true \
#    && php artisan route:clear || true \
#    && php artisan cache:clear || true \
#    && php artisan view:clear || true
# 9. Completely delete any static local machine config files if they exist
RUN rm -f bootstrap/cache/config.php \
    && rm -f bootstrap/cache/routes.php \
    && rm -f bootstrap/cache/views.php

# 10. Expose Render's default port
EXPOSE 10000

# 11. Execute database upgrades and fire up Apache in the foreground
#CMD php artisan migrate --force && \
#    php artisan db:seed --force && \
#    echo "🚀 Schema ready. Launching Apache on Port 10000..." && \
#    apache2-foreground

# 11. Clear compiled configuration caches at runtime, then launch Apache
#CMD php artisan config:clear && \
#    php artisan cache:clear && \
#    php artisan view:clear && \
#    php artisan route:clear && \
#    php artisan migrate --force && \
#    php artisan db:seed --force && \
#    echo "🚀 Schema verified. Launching Apache on Port 10000..." && \
#    apache2-foreground

# 11. Run your operations dynamically at runtime when variables are accessible
#CMD php artisan config:clear && \
#    php artisan cache:clear && \
#    php artisan view:clear && \
#    php artisan route:clear && \
#    php artisan migrate --force && \
#    php artisan db:seed --force && \
#    echo "🚀 Configuration clear. Postgres Connected. Launching Apache..." && \
#    apache2-foreground

# 11. Run migrations and seeders, then launch Apache directly
#CMD php artisan migrate --force && \
#    php artisan db:seed --force && \
#    echo "🚀 Schema and seeds complete. Launching Apache web instance..." && \
#    apache2-foreground


# 11. Override storage paths to use the writable /tmp block at runtime
CMD export VIEW_COMPILED_PATH=/tmp/storage/framework/views && \
    mkdir -p /tmp/storage/framework/views /tmp/storage/framework/cache /tmp/storage/framework/sessions && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    echo "🚀 Storage paths bound to /tmp. Launching Apache..." && \
    apache2-foreground
