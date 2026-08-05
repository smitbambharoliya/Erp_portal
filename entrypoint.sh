#!/bin/sh
set -e
php bin/console cache:clear
php bin/console assets:install public
exec php -S 0.0.0.0:$PORT -t public public/index.php
