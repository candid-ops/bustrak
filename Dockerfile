FROM php:8.1-apache

# Install dependencies including SQLite
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip unzip git curl libzip-dev sqlite3 libsqlite3-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql mbstring exif pcntl bcmath zip pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files (including .env)
COPY . /var/www/app/

WORKDIR /var/www/app

# Create storage and cache directories
RUN mkdir -p /var/www/app/storage/framework/{sessions,views,cache}
RUN mkdir -p /var/www/app/bootstrap/cache

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Create SQLite database
RUN touch /var/www/app/database/database.sqlite

# Generate app key (now .env exists)
RUN php artisan key:generate --force

# Run migrations
RUN php artisan migrate --force || true

# Set permissions
RUN chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache /var/www/app/database
RUN chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache /var/www/app/database

# Configure Apache
RUN a2enmod rewrite
RUN rm -rf /var/www/html && ln -s /var/www/app/public /var/www/html
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN cat > /etc/apache2/conf-available/laravel.conf <<'EOF'
<Directory /var/www/html>
    Options Indexes FollowSymLinks
    AllowOverride None
    Require all granted
    
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [L]
    </IfModule>
</Directory>
EOF

RUN a2enconf laravel

EXPOSE 8080
CMD ["apache2-foreground"]
