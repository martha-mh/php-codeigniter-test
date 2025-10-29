FROM php:7.4-apache

# Extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql && a2enmod rewrite

# Habilitar .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar script de inicialización
COPY docker/init-db.sh /usr/local/bin/init-db.sh
RUN chmod +x /usr/local/bin/init-db.sh

EXPOSE 80
