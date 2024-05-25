# Use the official PHP image with Apache
FROM php:7.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    curl

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_mysql intl zip opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.22

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Adjust Apache configuration to handle Symfony
# Symfony requires AllowOverride All to allow the .htaccess file to work correctly
# Update the Apache DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/web|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/web|' /etc/apache2/sites-available/default-ssl.conf
RUN sed -i 's|/var/www/html|/var/www/html/web|' /etc/apache2/apache2.conf
RUN sed -i '/<Directory \/var\/www\/html\/web>/,/<\/Directory>/ {\
    s/AllowOverride None/AllowOverride All/;\
    s/DirectoryIndex index.php index.html/DirectoryIndex html\/web\/app_dev.php/;\
}' /etc/apache2/apache2.conf

# Copy application source
COPY . /var/www/html

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Use Composer to install PHP dependencies
RUN composer install --no-interaction

# Set permissions for cache and logs
RUN chmod -R 777 var/cache var/logs

RUN php bin/console cache:clear --env=dev

# Expose port 80
EXPOSE 80

# Start Apache server in the foreground
CMD ["apache2-foreground"]
