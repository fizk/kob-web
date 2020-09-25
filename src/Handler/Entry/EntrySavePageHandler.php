<?php declare(strict_types=1);

namespace App\Handler\Entry;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Router\RouterInterface;
use App\Service\{Entry, Search};
use DateTime;

class EntrySavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private Entry $entry;
    private Search $search;

    public function __construct(RouterInterface $router, Entry $entry, Search $search)
    {
        $this->router = $router;
        $this->entry  = $entry;
        $this->search = $search;
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
            'from' => $post['from'],
            'to' => $post['to'],
            'type' => $post['type'],
            'orientation' => $post['orientation'],
            'affected' => (new DateTime())->format('Y-m-d H:i:s')
        ];
        $data = array_merge($data, $id ? [] : [
            'created' => (new DateTime())->format('Y-m-d H:i:s'),
        ]);

        $insertedId = $this->entry->save($data);
        $this->entry->attachAuthors((string) $insertedId, isset($post['author']) ? $post['author'] : []);
        $this->entry->attachImages((string) $insertedId, isset($post['poster']) ? $post['poster'] : [], 1);
        $this->entry->attachImages((string) $insertedId, isset($post['gallery']) ? $post['gallery'] : [], 2);

        $entry = $this->entry->get((string) $insertedId);

        $this->search->save($entry);

        return new RedirectResponse($this->router->generateUri('entry', ['id' => $insertedId]));
    }
}
