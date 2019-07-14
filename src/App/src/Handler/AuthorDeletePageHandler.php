<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router;
use App\Service;

class AuthorDeletePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var \App\Service\Author */
    private $author;

    public function __construct(Router\RouterInterface $router, Service\Author $author) {
        $this->router = $router;
        $this->author  = $author;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->author->delete($request->getAttribute('id'));

        return new RedirectResponse($this->router->generateUri('authors'));
    }
}
