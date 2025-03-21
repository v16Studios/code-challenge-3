FROM php:8.2-fpm

# Install PHP extensions, Composer, and dependencies (if required)
RUN apt-get update && apt-get install -y \
   unzip \
   curl \
   git \
   libmcrypt-dev \
   && docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer install

# Add entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
