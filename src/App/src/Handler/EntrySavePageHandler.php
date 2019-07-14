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

class EntrySavePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var \App\Service\Entry */
    private $entry;

    /** @var \App\Service\Author */
    private $author;

    /** @var \App\Service\Image */
    private $image;

    public function __construct(
        Router\RouterInterface $router,
        Service\Entry $entry,
        Service\Author $author,
        Service\Image $image
    ) {
        $this->router = $router;
        $this->entry  = $entry;
        $this->author = $author;
        $this->image  = $image;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();

        $data = [
            'id' => $id,
            'title' => $post['title'],
            'body' => $post['body'],
            'from' => $post['from'],
            'to' => $post['to'],
            'type' => $post['type'],
            'affected' => (new DateTime())->format('Y-m-d H:i:s')
        ];
        $data = array_merge($data, $id ? [] : [
            'created' => (new DateTime())->format('Y-m-d H:i:s'),
        ]);

        $id = $this->entry->save($data);
        $this->entry->attachAuthors((string) $id, $post['author']);
        $this->entry->attachImages((string) $id, isset($post['poster']) ? $post['poster'] : [], 1);
        $this->entry->attachImages((string) $id, isset($post['gallery']) ? $post['gallery'] : [], 2);

        return new RedirectResponse($this->router->generateUri('entry', ['id' => $id]));
    }
}
