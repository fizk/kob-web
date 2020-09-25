<?php declare(strict_types=1);

namespace App\Handler\Author;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse, JsonResponse};
use App\Router\RouterInterface;
use App\Service\Author;
use DateTime;

class AuthorSavePageHandler implements RequestHandlerInterface
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
