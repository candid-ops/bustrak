FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip unzip git curl libzip-dev sqlite3 libsqlite3-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql mbstring exif pcntl bcmath zip pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/app/
WORKDIR /var/www/app

# Create .env
RUN echo "APP_NAME=BusTrak" > .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_URL=https://bustrak-1l6c.onrender.com" >> .env && \
    echo "APP_KEY=base64:d0fVpKtWG3HJL5nMpQrS7wXyZ2aBcDeFgHiJkLmNoPq=" >> .env && \
    echo "LOG_CHANNEL=stack" >> .env && \
    echo "LOG_LEVEL=debug" >> .env && \
    echo "DB_CONNECTION=sqlite" >> .env && \
    echo "DB_DATABASE=/var/www/app/database/database.sqlite" >> .env

RUN mkdir -p /var/www/app/storage/framework/sessions && \
    mkdir -p /var/www/app/storage/framework/views && \
    mkdir -p /var/www/app/storage/framework/cache && \
    mkdir -p /var/www/app/bootstrap/cache && \
    mkdir -p /var/www/app/database && \
    touch /var/www/app/database/database.sqlite && \
    chmod 666 /var/www/app/database/database.sqlite

# Install composer WITHOUT any scripts
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs --no-scripts

# Skip all artisan commands to avoid provider errors

RUN chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache /var/www/app/database && \
    chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache /var/www/app/database

# Apache configuration
RUN a2enmod rewrite
RUN rm -rf /var/www/html && ln -s /var/www/app/public /var/www/html
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -i 's/AllowOverride All/AllowOverride None/g' /etc/apache2/apache2.conf

EXPOSE 8080
CMD ["apache2-foreground"]
