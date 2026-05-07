FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip unzip git curl libzip-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql mbstring exif pcntl bcmath zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html/

WORKDIR /var/www/html

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enable rewrite module
RUN a2enmod rewrite

# Create Apache configuration directly
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Replace the default site with our configuration
RUN cat > /etc/apache2/sites-available/000-default.conf <<'EOF'
<VirtualHost *:8080>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog /error.log
    CustomLog /access.log combined
</VirtualHost>
EOF

# Enable the site
RUN a2ensite 000-default.conf

EXPOSE 8080

CMD ["apache2-foreground"]
