# Utiliser l'image PHP officielle avec Apache
FROM php:8.2-apache

# Installation des dépendances système et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Activer le module Apache mod_rewrite
RUN a2enmod rewrite

# Copier les fichiers de l'application
COPY . /var/www/html/

# Définir les permissions appropriées
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposer le port 80
EXPOSE 80