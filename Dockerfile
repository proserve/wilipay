FROM richarvey/nginx-php-fpm:1.4.1

ENV WEBROOT=/var/www/html/public/
ENV SKIP_COMPOSER=1

COPY . /var/www/html/
WORKDIR /var/www/html/

RUN chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

RUN composer install --no-interaction

EXPOSE 80