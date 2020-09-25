<?php declare(strict_types=1);

namespace App\Handler\Author;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{JsonResponse};
use App\Service\Author;

class AuthorsSearchPageHandler implements RequestHandlerInterface
{
    private Author $author;

    public function __construct(Author $author)
    {
        $this->author   = $author;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new JsonResponse($this->author->search($request->getQueryParams()['q']));
    }
}
