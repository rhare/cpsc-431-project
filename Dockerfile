FROM php:7.2.1-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY src/ /var/www/html/
COPY data/ /var/www/data/
RUN chown -R www-data:www-data /var/www/data/

