FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Script สำหรับ replace PORT ก่อน start Apache
RUN echo '#!/bin/sh\n\
PORT="${PORT:-80}"\n\
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-enabled/000-default.conf\n\
exec apache2ctl -D FOREGROUND' > /start.sh \
    && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
