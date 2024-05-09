# Usar a imagem oficial do PHP 8.3 com Apache
FROM php:8.3-apache

# Instalar as extensões do PHP necessárias para o Laravel
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libwebp-dev \
        libzip-dev \
        zip \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip exif pcntl

# Habilitar o mod_rewrite do Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar o diretório público como raiz do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permitir .htaccess com regras de reescrita
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configurar permissões (Ajuste conforme necessário)
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html
COPY . /var/www/html/

RUN composer install --no-interaction --optimize-autoloader --no-dev

# Expor a porta 80
EXPOSE 80 443

# Configurar o ponto de entrada para o Apache em foreground
CMD ["apache2-foreground"]
