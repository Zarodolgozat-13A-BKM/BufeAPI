# Base image
FROM php:8.4-fpm

WORKDIR /var/www
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libldap2-dev \
    default-mysql-client \
    supervisor \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip ldap pcntl sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --optimize-autoloader --no-dev
RUN chown -R www-data:www-data /var/www
# Install Composer
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
# WORKDIR /var/www/html

# Copy project files
# COPY . .

# COPY .env.example .env


# Install Laravel dependencies
# RUN composer install --no-dev --optimize-autoloader
# RUN php artisan key:generate
# RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
# RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
# RUN php artisan optimize
# COPY entrypoint.sh /entrypoint.sh
# RUN chmod +x /entrypoint.sh

RUN php artisan config:clear
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan storage:link

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000 8080

CMD ["/usr/bin/supervisord"]
