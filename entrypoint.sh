#!/bin/sh
set -e
php bin/console cache:clear
exec php -S 0.0.0.0:$PORT -t public public/index.php
