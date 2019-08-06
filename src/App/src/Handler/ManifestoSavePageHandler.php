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

class ManifestoSavePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var \App\Service\Manifesto */
    private $manifesto;

    public function __construct(Router\RouterInterface $router, Service\Manifesto $manifesto) {
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
