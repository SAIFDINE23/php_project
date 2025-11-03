# Utiliser l'image PHP officielle avec Apache
FROM php:8.2-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip \
    && docker-php-ext-install mysqli pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Activer mod_rewrite
RUN a2enmod rewrite

# Copier tous les fichiers du projet dans le dossier web du conteneur
COPY . /var/www/html/

# Permissions correctes
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposer le port Apache
EXPOSE 80
