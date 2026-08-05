#!/bin/sh
set -e
php bin/console cache:clear
php bin/console asset-map:compile
exec php -S 0.0.0.0:$PORT -t public public/index.php
