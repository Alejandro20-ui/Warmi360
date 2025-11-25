# === FASE 1: Construcción (opcional, pero útil para futuras optimizaciones) ===
FROM php:8.3-apache AS base

# Instalar dependencias del sistema y extensiones PHP esenciales
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype-dev \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libonig-dev \
        libxml2-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        opcache \
        intl \
        mbstring && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# === Configuración de Apache ===
# Habilitar mod_rewrite (importante para URLs limpias)
RUN a2enmod rewrite

# Desactivar la firma del servidor (seguridad)
RUN { \
        echo 'ServerTokens Prod'; \
        echo 'ServerSignature Off'; \
    } >> /etc/apache2/conf-enabled/security.conf

# === Copiar tu aplicación ===
WORKDIR /var/www/html
COPY . .

# === Permisos seguros ===
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/ && \
    chmod -R 644 /var/www/html/

# === Puerto expuesto ===
EXPOSE 80

# === Comando de arranque ===
CMD ["apache2-foreground"]