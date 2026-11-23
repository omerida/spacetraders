#!/bin/bash
set -e

CONTAINER="php-daemon"
docker compose run \
   --rm \
   --workdir /var/www \
   "$CONTAINER" \
   php ./fleetcom/loop.php "$@"

