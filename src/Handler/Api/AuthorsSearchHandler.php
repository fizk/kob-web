<?php declare(strict_types=1);

namespace App\Handler\Api;

use App\Service\AuthorService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{JsonResponse};

class AuthorsSearchHandler implements RequestHandlerInterface
{
    private AuthorService $author;

    public function __construct(AuthorService $author)
    {
        $this->author   = $author;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new JsonResponse(
            $this->author->search($request->getQueryParams()['q'])
        );
    }
}
