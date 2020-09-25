<?php

// Delegate static file requests back to the PHP built-in webserver
// if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
//     return false;
// }

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

set_error_handler("exception_error_handler");

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\ServiceManager\ServiceManager;
use App\Router\RouterInterface;
use function App\Router\dispatch;

$serviceManager = new ServiceManager(require './config/service.php');
$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$collection = $serviceManager->get(RouterInterface::class);
$collection->setRouteConfig(require './config/router.php');
$response = dispatch($request, $collection, $serviceManager);

(new SapiEmitter)->emit($response);

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
