<?php declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{JsonResponse};
use App\Service\Search;

class ApiSearchPageHandler implements RequestHandlerInterface
{
    private Search $search;

    public function __construct(Search $search)
    {
        $this->search    = $search;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $language = $request->getAttribute('language', 'is');
        $query = $request->getQueryParams();
        if (array_key_exists('q', $query)) {
            $response = $this->search->search($query['q'], $language);
            return new JsonResponse($response);
        } else {
            return new JsonResponse([
                'count' => 0,
                'results' => [],
                'query' => ''
            ]);
        }
    }
}
