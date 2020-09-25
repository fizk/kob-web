<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Router\RouterInterface;
use App\Service\Manifesto;

class ManifestoSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private Manifesto $manifesto;

    public function __construct(RouterInterface $router, Manifesto $manifesto)
    {
        $this->router    = $router;
        $this->manifesto = $manifesto;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        $post = $request->getParsedBody();
        $data = [
            'id' => $id,
            'type' => $post['type'],
            'body_is' => $post['body_is'],
            'body_en' => $post['body_en'],
        ];

        $this->manifesto->save($data);
        $this->manifesto->attachImages($id, isset($post['gallery']) ? $post['gallery'] : []);

        return new RedirectResponse($this->router->generateUri('about'));
    }
}
