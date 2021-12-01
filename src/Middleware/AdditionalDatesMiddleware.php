<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use DateTime;

class AdditionalDatesMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request->withParsedBody(array_merge(
            $request->getParsedBody(),
            [
                'id' => $request->getAttribute('id')
            ],
            [
                'affected' => (new DateTime())->format('Y-m-d H:i:s'),
            ],
            $request->getAttribute('id') ? [] : [
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
            ]
        )));
    }
}
