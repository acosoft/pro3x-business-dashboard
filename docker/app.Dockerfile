ARG BASE_IMAGE=ghcr.io/acosoft/business-dashboard-base:latest
FROM ${BASE_IMAGE}

USER root

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

COPY --chown=www-data:www-data . /var/www/html/

USER www-data

RUN rm -rf app/cache/*

EXPOSE 80

CMD ["apache2-foreground"]
