<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Neutraliser l'avertissement tempnam() propre aux environnements mutualisés (OVH)
$previousHandler = set_error_handler(function ($errno, $errstr, $errfile = '', $errline = 0) use (&$previousHandler) {
    if (str_contains($errstr, 'tempnam()')) {
        return true;
    }
    if (is_callable($previousHandler)) {
        return call_user_func($previousHandler, $errno, $errstr, $errfile, $errline);
    }
    return false;
});

$app->handleRequest(Request::capture());
