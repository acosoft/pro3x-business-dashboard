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

RUN a2enmod rewrite && a2enmod headers

RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install gd
RUN docker-php-ext-install mysql mysqli pdo pdo_mysql
RUN docker-php-ext-install curl intl mbstring exif bcmath bz2 calendar soap sockets
RUN docker-php-ext-install mcrypt xmlrpc zip
RUN docker-php-ext-install sysvmsg sysvsem sysvshm
