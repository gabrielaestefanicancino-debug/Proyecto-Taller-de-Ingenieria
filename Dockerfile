FROM php:8.2-apache

# Instalar la extensión PDO MySQL necesaria para conectar PHP con la BD
RUN docker-php-ext-install pdo pdo_mysql

# Exponer el puerto 80 del contenedor
EXPOSE 80
