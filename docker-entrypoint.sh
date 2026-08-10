#!/usr/bin/env bash
set -e

# SQLite only — no database server. Create the schema and seed it before Apache starts,
# so the very first request already has data.
php -r 'require "/var/www/html/config/db.php"; db_init();' || true
chown -R www-data:www-data /var/www/data

exec apache2-foreground
