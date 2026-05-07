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

# Create .env with static APP_KEY
RUN echo "APP_NAME=BusTrak" > .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_URL=https://bustrak-1l6c.onrender.com" >> .env && \
    echo "APP_KEY=base64:YOUR_APP_KEY_HERE_32_CHARACTERS_LONG=" >> .env && \
    echo "LOG_CHANNEL=stack" >> .env && \
    echo "LOG_LEVEL=debug" >> .env && \
    echo "DB_CONNECTION=sqlite" >> .env && \
    echo "DB_DATABASE=/var/www/app/database/database.sqlite" >> .env && \
    echo "MPESA_ENV=sandbox" >> .env && \
    echo "MPESA_CONSUMER_KEY=TUlgpGC3FTnHLMfCNF8KUeqND6Lh74FjXr6fsGx7EZiRXKtC" >> .env && \
    echo "MPESA_CONSUMER_SECRET=kto47KYNoyvMtw1DF291rffTtriPSUSKr3tQIvr7mFwfYbLAyfEdShuxWlXYaFYd" >> .env && \
    echo "MPESA_SHORTCODE=174379" >> .env && \
    echo "MPESA_PASSKEY=bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919" >> .env && \
    echo "MPESA_CALLBACK_URL=https://bustrak-1l6c.onrender.com/api/mpesa/callback" >> .env && \
    echo "MAIL_MAILER=smtp" >> .env && \
    echo "MAIL_HOST=smtp.gmail.com" >> .env && \
    echo "MAIL_PORT=587" >> .env && \
    echo "MAIL_USERNAME=ordinaryfox479@gmail.com" >> .env && \
    echo "MAIL_PASSWORD=orluaqjdcassfqpa" >> .env && \
    echo "MAIL_ENCRYPTION=tls" >> .env && \
    echo "MAIL_FROM_ADDRESS=ordinaryfox479@gmail.com" >> .env && \
    echo "MAIL_FROM_NAME=BusTrak" >> .env

# Create directories
RUN mkdir -p /var/www/app/storage/framework/sessions && \
    mkdir -p /var/www/app/storage/framework/views && \
    mkdir -p /var/www/app/storage/framework/cache && \
    mkdir -p /var/www/app/bootstrap/cache && \
    mkdir -p /var/www/app/database

# Create SQLite database
RUN touch /var/www/app/database/database.sqlite && \
    chmod 666 /var/www/app/database/database.sqlite

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs --no-scripts

# Run migrations
RUN php artisan migrate --force || true

# Set permissions
RUN chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache /var/www/app/database && \
    chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache /var/www/app/database

# Configure Apache
RUN a2enmod rewrite
RUN rm -rf /var/www/html && ln -s /var/www/app/public /var/www/html
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Allow .htaccess overrides
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

EXPOSE 8080
CMD ["apache2-foreground"]
