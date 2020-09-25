<?php declare(strict_types=1);

namespace App\Handler\Entry;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Router\RouterInterface;
use App\Service\Entry;

class EntryDeletePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private Entry $entry;

    public function __construct(RouterInterface $router, Entry $entry)
    {
        $this->router = $router;
        $this->entry  = $entry;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->entry->delete($request->getAttribute('id'));

        return new RedirectResponse($this->router->generateUri('update'));
    }
}
