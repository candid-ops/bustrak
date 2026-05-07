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

# Copy custom Apache config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Force Apache to use our config
RUN a2dissite 000-default.conf || true
RUN a2ensite 000-default.conf
RUN a2enmod rewrite

# Override default document root
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/apache2.conf || true

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 8080

CMD ["apache2-foreground"]
