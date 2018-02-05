FROM php:7.2-fpm-alpine3.6

COPY . /var/www/html
WORKDIR /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer --install-dir=$PWD

#TODO: fix: not run composer as root
RUN ./composer install
CMD php artisan serve --port=80 --host=0.0.0.0
EXPOSE 80 443
