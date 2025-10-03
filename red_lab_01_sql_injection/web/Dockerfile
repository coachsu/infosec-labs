FROM php:8.1-apache

# install extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# copy source
COPY src/ /var/www/html/

# adjust permissions (if needed)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
