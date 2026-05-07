FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j\ gd pdo_mysql mbstring exif pcntl bcmath zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy ALL application files (including hidden files)
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Debug: List files to verify copy worked
RUN ls -la /var/www/html/
RUN ls -la /var/www/html/public/ || true

# Install composer dependencies
RUN composer install --no-interaction --no-progress --optimize-autoloader --no-dev --ignore-platform-reqs

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache VirtualHost properly
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN rm -f /etc/apache2/sites-available/000-default.conf

# Create Apache config
RUN echo '<VirtualHost *:8080>' > /etc/apache2/sites-available/000-default.conf && \
    echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Options Indexes FollowSymLinks MultiViews' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Verify Apache config
RUN apache2ctl -t

EXPOSE 8080

CMD ["apache2-foreground"]
