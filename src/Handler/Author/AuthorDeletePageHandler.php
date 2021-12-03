<?php

namespace App\Handler\Author;

use App\Router\RouterInterface;
use App\Service\AuthorService;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\{RedirectResponse};

class AuthorDeletePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private AuthorService $author;

    public function __construct(RouterInterface $router, AuthorService $author)
    {
        $this->router = $router;
        $this->author  = $author;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->author->delete($request->getAttribute('id'));
        return new RedirectResponse($this->router->generateUri('authors'));
    }
}
