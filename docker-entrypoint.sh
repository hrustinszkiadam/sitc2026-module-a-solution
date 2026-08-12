#!/usr/bin/env bash
set -e

# The MySQL server is remote, so it may be briefly unreachable. Try to create and seed
# the schema before Apache starts; if it fails, start anyway — the pages report the
# error themselves and the next request retries.
php -r 'require "/var/www/html/config/db.php"; db_init();' || \
    echo "entrypoint: db_init failed — starting Apache anyway" >&2

exec apache2-foreground
