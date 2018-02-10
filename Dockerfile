FROM proserve/nginx-php-fpm-laravel:0.1.0

COPY . /var/www/html/
WORKDIR /var/www/html/

RUN chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

# RUN composer install --no-interaction

EXPOSE 80