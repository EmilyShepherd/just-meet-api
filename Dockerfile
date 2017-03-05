FROM php:7-fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install sockets 
RUN docker-php-ext-install bcmath 