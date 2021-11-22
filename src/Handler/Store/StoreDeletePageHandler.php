<?php declare(strict_types=1);

namespace App\Handler\Store;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Router\RouterInterface;
use App\Service\Store;

class StoreDeletePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private Store $store;

    public function __construct(RouterInterface $router, Store $store)
    {
        $this->router = $router;
        $this->store  = $store;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->store->delete($request->getAttribute('id'));

        return new RedirectResponse($this->router->generateUri('update'));
    }
}
