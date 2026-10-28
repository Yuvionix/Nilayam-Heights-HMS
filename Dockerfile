# Production Dockerfile for Nilayam Heights Society Management System & Yadav PG
# Engineered by Yuvraj — Self-Contained Deployment (Built-in MariaDB + Dynamic Port)

FROM php:8.2-apache

# Install system dependencies, MySQL/MariaDB server and PHP extensions
RUN apt-get update && apt-get install -y \
    mariadb-server \
    mariadb-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Set working directory to Apache webroot
WORKDIR /var/www/html

# Copy project files into webroot
COPY . /var/www/html/

# Copy dynamic port and database entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set file permissions for Apache user
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose standard HTTP port (overridden dynamically by entrypoint.sh via $PORT)
EXPOSE 80

# Dynamic port binding and DB initialization entrypoint
ENTRYPOINT ["entrypoint.sh"]

# Start Apache in foreground
CMD ["apache2-foreground"]
