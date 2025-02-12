#!/bin/bash
set -e

CONTAINER="php"
docker compose run \
   --rm \
   --workdir /var/www \
   "$CONTAINER" \
   composer "$@"

