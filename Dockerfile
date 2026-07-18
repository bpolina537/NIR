FROM php:8.2-apache
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
COPY public/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
