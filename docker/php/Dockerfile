FROM php:7.4-apache

# Install system dependencies and common PHP extensions
RUN apt-get update \
     && apt-get install -y --no-install-recommends \
         libpng-dev \
         libzip-dev \
         zip \
         unzip \
         git \
         curl \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
