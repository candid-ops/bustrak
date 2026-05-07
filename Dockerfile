FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip unzip git curl libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql mbstring exif pcntl bcmath zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/app/
WORKDIR /var/www/app

RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

RUN chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache
RUN chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache

RUN a2enmod rewrite
RUN rm -rf /var/www/html && ln -s /var/www/app/public /var/www/html
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configure Apache with Laravel routing (no .htaccess needed)
RUN echo '<Directory /var/www/html>' > /etc/apache2/conf-available/laravel.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/conf-available/laravel.conf && \
    echo '    AllowOverride None' >> /etc/apache2/conf-available/laravel.conf && \
    echo '    Require all granted' >> /etc/apache2/conf-available/laravel.conf && \
    echo '' >> /etc/apache2/conf-available/laravel.conf && \
    echo '    <IfModule mod_rewrite.c>' >> /etc/apache2/conf-available/laravel.conf && \
    echo '        RewriteEngine On' >> /etc/apache2/conf-available/laravel.conf && \
    echo '        RewriteCond %{REQUEST_FILENAME} !-d' >> /etc/apache2/conf-available/laravel.conf && \
    echo '        RewriteCond %{REQUEST_FILENAME} !-f' >> /etc/apache2/conf-available/laravel.conf && \
    echo '        RewriteRule ^ index.php [L]' >> /etc/apache2/conf-available/laravel.conf && \
    echo '    </IfModule>' >> /etc/apache2/conf-available/laravel.conf && \
    a2enconf laravel

EXPOSE 8080
CMD ["apache2-foreground"]
