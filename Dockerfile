# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias (PDO MySQL)
RUN docker-php-ext-install pdo_mysql

# Copiar todos los archivos del proyecto al directorio web del servidor
COPY . /var/www/html/

# Asegurarse de que Apache pueda leer los archivos
RUN chown -R www-data:www-data /var/www/html/

# Exponer el puerto 80 (opcional, ya que Apache lo hace por defecto)
EXPOSE 80

# Comando para iniciar Apache (ya está en la imagen base)
CMD ["apache2-foreground"]