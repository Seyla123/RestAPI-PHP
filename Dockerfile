FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Enable Apache rewrite module (optional for frameworks)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html