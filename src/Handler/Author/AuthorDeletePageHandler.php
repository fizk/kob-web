<?php declare(strict_types=1);

namespace App\Handler\Author;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Router\RouterInterface;
use App\Service\Author;

class AuthorDeletePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private Author $author;

    public function __construct(RouterInterface $router, Author $author)
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
