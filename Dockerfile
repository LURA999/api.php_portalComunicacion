# Usa una imagen base de PHP con Apache
FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql
    # iputils-ping

# Habilita mod_rewrite de Apache (si es necesario)
RUN a2enmod rewrite

# Copia los archivos de tu proyecto en el contenedor
COPY portal_api/ /var/www/html

# Expon el puerto 80 para acceder a tu aplicación
EXPOSE 80
