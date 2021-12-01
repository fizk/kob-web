<?php declare(strict_types=1);

namespace App\Handler\Store;

use App\Router\RouterInterface;
use App\Service\StoreService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};

class StoreDeletePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private StoreService $store;

    public function __construct(RouterInterface $router, StoreService $store)
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
