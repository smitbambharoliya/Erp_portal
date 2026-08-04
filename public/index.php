<?php

use App\Kernel;

putenv('APP_RUNTIME_OPTIONS=disable_dotenv=1');
$_SERVER['APP_RUNTIME_OPTIONS'] = ['disable_dotenv' => true];
$_ENV['APP_RUNTIME_OPTIONS'] = ['disable_dotenv' => true];

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context) {
    return new Kernel($context['APP_ENV'] ?? 'prod', (bool) ($context['APP_DEBUG'] ?? false));
};
