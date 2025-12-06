FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL/MariaDB
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite (IMPORTANTE para Laravel)
RUN a2enmod rewrite

# Copiar todo tu proyecto al contenedor
WORKDIR /var/www/html
COPY . .

# Dar permisos a Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
