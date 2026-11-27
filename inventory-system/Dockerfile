
FROM php:8.2-apache

# Install SQLite support for PHP
RUN docker-php-ext-install pdo_sqlite

# Configure Apache to use Render's port
RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:10000>/' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

# Copy website files
COPY . /var/www/html/

# Allow PHP to write to the SQLite database
RUN chown -R www-data:www-data /var/www/html/database \
    && chmod 775 /var/www/html/database \
    && chmod 664 /var/www/html/database/inventory.db

EXPOSE 10000
