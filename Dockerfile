FROM php:7-apache

RUN apt-get update && apt-get install nano

COPY extras/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY extras/start-apache /usr/local/bin
COPY --chown=www-data:www-data app /var/www/html

ARG WP_DB_HOST
ARG WP_DB_NAME
ARG WP_DB_PASSWORD
ARG WP_DB_USER
ARG WP_S3_ACCESS_KEY
ARG WP_S3_SECRET_KEY

ENV WP_DB_HOST=$WP_DB_HOST
ENV WP_DB_NAME=$WP_DB_NAME
ENV WP_DB_PASSWORD=$WP_DB_PASSWORD
ENV WP_DB_USER=$WP_DB_USER
ENV WP_S3_ACCESS_KEY=$WP_S3_ACCESS_KEY
ENV WP_S3_SECRET_KEY=$WP_S3_SECRET_KEY

CMD ["start-apache"]