#!/bin/sh
set -eu

mkdir -p storage/app/public

if [ ! -e public/storage ] && [ ! -L public/storage ]; then
    ln -s ../storage/app/public public/storage
fi

exec "$@"
