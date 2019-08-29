<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router;
use App\Service;
use DateTime;

class AuthorSavePageHandler implements RequestHandlerInterface
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
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();
        $data = [
            'id' => $id,
            'name' => $post['name'],
            'affected' => (new DateTime())->format('Y-m-d H:i:s'),
        ];
        $data = array_merge($data, $id ? [] : [
            'created' => (new DateTime())->format('Y-m-d H:i:s'),
        ]);

        $createdId = $this->author->save($data);
        $withHeaders = $request->getHeader('X-REQUESTED-WITH');

        return in_array('xmlhttprequest', $withHeaders)
            ? new JsonResponse([
                'id' => $createdId,
                'name' => $post['name'],
            ])
            : new RedirectResponse($this->router->generateUri('author', ['id' => $createdId]));
    }
}
