<?php declare(strict_types=1);

namespace App\Handler\Entry;

use App\Router\RouterInterface;
use App\Service\EntryService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};

class EntryDeletePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private EntryService $entry;

    public function __construct(RouterInterface $router, EntryService $entry)
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
