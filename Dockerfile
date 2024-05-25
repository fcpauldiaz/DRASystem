# Use the official PHP image with Apache
FROM php:7.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
# Including recommended libonig-dev for mbstring PHP extension
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    curl \
    libonig-dev \
    && apt-get clean \  # Clean up to reduce image size

RUN rm -rf /var/lib/apt/lists/*  # Remove apt cache to reduce image size

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql intl zip opcache mbstring

# Install Composer securely, without specifying version to use the latest stable release
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.22

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Adjust Apache configuration to handle Symfony properly in production
# Changing document root and updating Directory directives
RUN sed -i 's|/var/www/html|/var/www/html/web|' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/web|' /etc/apache2/sites-available/default-ssl.conf \
    && sed -i 's|/var/www/html|/var/www/html/web|' /etc/apache2/apache2.conf \
    && sed -i '/<Directory \/var\/www\/html\/web>/,/<\/Directory>/ {\
        s/AllowOverride None/AllowOverride All/;\
        s/DirectoryIndex .*$/DirectoryIndex app.php/;}' /etc/apache2/apache2.conf

# Copy application source
COPY . /var/www/html

# Optimize Composer for production
RUN composer install --optimize-autoloader --no-interaction

RUN php bin/console cache:clear --env=prod

# Create cache and logs directories and set permissions
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/cache/prod  /var/www/html/var/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/var/cache /var/www/html/var/cache/prod /var/www/html/var/logs

# Expose port 80
EXPOSE 80

# Start Apache server in the foreground
CMD ["apache2-foreground"]
