FROM php:8.2-fpm-alpine

# Install helper script for fast PHP extensions installation
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install system dependencies & PHP extensions
RUN apk add --no-cache nginx supervisor \
    && install-php-extensions pdo_mysql gd bcmath intl opcache pcntl exif

# PHP config
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Supervisor config (manages php-fpm + nginx + worker)
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set permissions
RUN mkdir -p storage/logs storage/uploads /var/log/supervisor \
    && chmod -R 775 storage \
    && chown -R www-data:www-data storage

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
