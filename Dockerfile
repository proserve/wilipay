FROM proserve/nginx-php-fpm-laravel:0.1.0

ADD nginx-site.conf /etc/nginx/sites-available/default.conf

COPY . /var/www/html/
WORKDIR /var/www/html/
RUN touch storage/logs/laravel.log
RUN chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

EXPOSE 80