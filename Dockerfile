# Base image
FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libldap2-dev \
    cron \
    default-mysql-client \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip ldap pcntl sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# COPY scheduler /etc/cron.d/scheduler

# RUN chmod 0644 /etc/cron.d/scheduler && \
#     crontab /etc/cron.d/scheduler
# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# COPY .env.example .env

# Install Laravel dependencies NO DEV KELL MAJD VISSZA
RUN composer install --optimize-autoloader
# RUN php artisan key:generate
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN php artisan optimize
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000
EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
