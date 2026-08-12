# PHP pinned to the WSC2026 spec. Apache serves index.php, index.html, JS and CSS
# from one document root — no extra web server config needed.
FROM php:8.3-apache

RUN docker-php-ext-install -j"$(nproc)" pdo_mysql

# Serve index.php first; index.html stays reachable at /index.html.
# Dotfiles are denied outright so nothing like .env or .git can be fetched over HTTP.
RUN printf 'DirectoryIndex index.php index.html\n\
<FilesMatch "^\\.">\n\
    Require all denied\n\
</FilesMatch>\n' > /etc/apache2/conf-available/app.conf \
    && a2enconf app

COPY . /var/www/html

# The credentials travel with the image (nothing sets env vars at deploy time), but the
# file is moved OUT of the document root so it can never be downloaded over HTTP.
RUN mv /var/www/html/.env /var/www/.env \
    && rm -f /var/www/html/Dockerfile /var/www/html/docker-compose.yml \
       /var/www/html/docker-entrypoint.sh

RUN chown -R www-data:www-data /var/www/html /var/www/.env \
    && chmod 640 /var/www/.env

COPY docker-entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# No ENV DB_* here on purpose: a real environment variable outranks /var/www/.env, so
# baking defaults in would silently override whatever the competitor puts in that file.

EXPOSE 80
ENTRYPOINT ["entrypoint"]
