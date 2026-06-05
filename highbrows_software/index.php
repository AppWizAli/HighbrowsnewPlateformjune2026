<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Path to your Laravel project root (one level up from public folder)
$root = dirname(__DIR__) . '/highbrows_software';

// If Laravel is in a subfolder, define base path accordingly
require $root . '/vendor/autoload.php';

// Maintenance mode check
if (file_exists($maintenance = $root . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

$app = require_once $root . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
