<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

set_error_handler("exception_error_handler");

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Response\HtmlResponse;
use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use function App\Router\dispatch;

$serviceManager = new ServiceManager(require './config/service.php');
$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

try {
    $collection = $serviceManager->get(RouterInterface::class);
    $collection->setRouteConfig(require './config/router.php');
    $response = dispatch($request, $collection, $serviceManager);
    (new SapiEmitter)->emit($response);
} catch (Throwable $e) {
    (new SapiEmitter)->emit(
        new HtmlResponse(
            $serviceManager->get(TemplateRendererInterface::class)->render('error::error', getenv('ENVIRONMENT') === 'development'
                ? ['message' => $e->getMessage(), 'trace' => $e->getTrace()] : ['message' => null, 'trace' => null]
            ),
            500
        )
    );
}

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
