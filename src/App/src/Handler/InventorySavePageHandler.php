<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router;
use App\Service;
use DateTime;

class InventorySavePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var \App\Service\Inventory */
    private $inventory;

    public function __construct(Router\RouterInterface $router, Service\Inventory $manifesto) {
        $this->router    = $router;
        $this->inventory = $manifesto;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();
        $data = [
            'id' => $id,
            'title' => $post['title'],
            'body_is' => $post['body_is'],
            'body_en' => $post['body_en'],
            'affected' => (new DateTime())->format('Y-m-d H:i:s')
        ];
        $data = array_merge($data, $id ? [] : [
            'created' => (new DateTime())->format('Y-m-d H:i:s'),
        ]);
        $insertedId = $this->inventory->save($data);
        $this->inventory->attachImages((string) $insertedId, isset($post['gallery']) ? $post['gallery'] : []);

        return new RedirectResponse($this->router->generateUri('store'));
    }
}
