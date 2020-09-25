<?php

namespace App\Router;

use mindplay\middleman\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

function dispatch(
    ServerRequestInterface $request,
    RouterInterface $collection,
    ContainerInterface $serviceManager
): ResponseInterface {
    $router = $collection->match($request);
    //@todo does feel a little weird :|
    foreach ($router->getAttributes() as $key => $value) {
        $request = $request->withAttribute($key, $value);
    }

    if (is_array($router->getHandler())) {
        $middleware = $router->getHandler();
        $handlers = array_pop($middleware);

        $dispatcher = new Dispatcher(array_map(function ($item) use ($serviceManager) {
            return $serviceManager->get($item);
        }, $middleware));
        return $dispatcher->process($request, $serviceManager->get($handlers));
    } else {
        return $serviceManager->get($router->getHandler())->handle($request);
    }
}
