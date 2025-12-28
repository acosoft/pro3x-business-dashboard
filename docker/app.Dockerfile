FROM php:5.5.38-apache

RUN rm -f /etc/apt/sources.list.d/*

RUN printf "deb http://archive.debian.org/debian jessie main contrib non-free\n\
deb http://archive.debian.org/debian jessie-backports main contrib non-free\n" \
    > /etc/apt/sources.list

RUN echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid && \
    echo 'Acquire::AllowInsecureRepositories "true";' >> /etc/apt/apt.conf.d/99no-check-valid && \
    echo 'Acquire::AllowDowngradeToInsecureRepositories "true";' >> /etc/apt/apt.conf.d/99no-check-valid

RUN apt-get update && apt-get install -y --allow-unauthenticated \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libmcrypt-dev \
    libbz2-dev \
    zlib1g-dev \
    unzip

RUN a2enmod rewrite
RUN a2enmod headers

RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install gd
RUN docker-php-ext-install mysql mysqli pdo pdo_mysql
RUN docker-php-ext-install curl intl mbstring exif bcmath bz2 calendar soap sockets
RUN docker-php-ext-install mcrypt xmlrpc zip
RUN docker-php-ext-install sysvmsg sysvsem sysvshm

WORKDIR /var/www/html

RUN echo "date.timezone=Europe/Zagreb" > /usr/local/etc/php/conf.d/docker-timezone.ini

RUN cat > /etc/apache2/sites-available/000-default.conf <<'EOF'
<VirtualHost *:80>
    DocumentRoot /var/www/html/web
    Alias /icons /var/www/html/web/icons
    <Directory /var/www/html/web/icons>
        Options FollowSymLinks
        Require all granted
    </Directory>
    <Directory /var/www/html/web>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

RUN curl -k -fsSL https://getcomposer.org/download/1.10.26/composer.phar \
    -o /usr/local/bin/composer && chmod +x /usr/local/bin/composer

COPY . /var/www/html/

RUN mkdir -p app/cache app/logs && chown -R www-data:www-data app/cache app/logs

EXPOSE 80

CMD ["apache2-foreground"]
