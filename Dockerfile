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

# Set working directory
WORKDIR /var/www/html

# DEBUG: Show what files were copied
RUN echo "=== Files in /var/www/html ===" && ls -la /var/www/html/
RUN echo "=== Files in /var/www/html/public ===" && ls -la /var/www/html/public/ || echo "public directory not found!"

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache (single line commands)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN rm -f /etc/apache2/sites-available/000-default.conf
RUN echo '<VirtualHost *:8080>' > /etc/apache2/sites-available/000-default.conf
RUN echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf
RUN echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf
RUN echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Verify Apache config
RUN apache2ctl -t

EXPOSE 8080

CMD ["apache2-foreground"]
