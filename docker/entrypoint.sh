#!/bin/sh
set -eu

if [ ! -L public/storage ]; then
    php artisan storage:link --relative
fi

exec "$@"
