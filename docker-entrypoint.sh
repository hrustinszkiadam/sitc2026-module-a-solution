#!/usr/bin/env bash
set -e

# Module A is a set of static pages plus two small PHP mini projects. No database is
# needed, so there is nothing to prepare before Apache starts.
exec apache2-foreground
