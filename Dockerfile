FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip unzip git curl libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql mbstring exif pcntl bcmath zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html/

WORKDIR /var/www/html

RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# DIRECT APPROACH: Modify the default Apache configuration
RUN a2enmod rewrite

# Change the default document root in apache2.conf
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/apache2.conf

# Also modify the directory directive
RUN sed -i 's|<Directory /var/www/html/>|<Directory /var/www/html/public>|g' /etc/apache2/apache2.conf

# Override the default site configuration completely
RUN echo '<VirtualHost *:8080>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Disable default site and re-enable
RUN a2dissite 000-default.conf || true
RUN a2ensite 000-default.conf

# Set ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Debug: Show what we changed
RUN grep -n "DocumentRoot" /etc/apache2/apache2.conf
RUN cat /etc/apache2/sites-available/000-default.conf

EXPOSE 8080

CMD ["apache2-foreground"]
