FROM php:5.5-apache
RUN docker-php-ext-install mysqli
RUN echo 'date.timezone = UTC' > /usr/local/etc/php/conf.d/custom.ini
RUN echo 'display_errors = On' >> /usr/local/etc/php/conf.d/custom.ini
RUN echo 'error_reporting = E_ALL' >> /usr/local/etc/php/conf.d/custom.ini
RUN echo 'track_errors = On' >> /usr/local/etc/php/conf.d/custom.ini
RUN echo 'html_errors = On' >> /usr/local/etc/php/conf.d/custom.ini
RUN echo 'display_startup_errors = On' >> /usr/local/etc/php/conf.d/custom.ini
RUN echo 'log_errors = On' >> /usr/local/etc/php/conf.d/custom.ini
