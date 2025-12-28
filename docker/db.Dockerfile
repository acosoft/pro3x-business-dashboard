FROM mysql:5.7

COPY install/database.sql /docker-entrypoint-initdb.d/01-database.sql
