<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../wooly-whims-core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../wooly-whims-core/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../wooly-whims-core/bootstrap/app.php';

// Tell Laravel that the public directory is HERE (public_html/shop), not wooly-whims-core/public
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
