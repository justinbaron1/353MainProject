FROM php:5.5-apache
RUN docker-php-ext-install mysqli
RUN echo 'date.timezone = UTC' > /usr/local/etc/php/conf.d/custom.ini
