<?php

declare(strict_types=1);

namespace App\Handler;

use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Service;


class ImageUpdatePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Image */
    private $image;

    public function __construct(
        Router\RouterInterface $router,
        Service\Image $image,
        TemplateRendererInterface $template
    ) {
        $this->router   = $router;
        $this->image    = $image;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();

        $affectedRows = $this->image->updateDescription($id, $post['description'], new \DateTime());

        return $affectedRows > 0
            ? new JsonResponse($this->image->get($id))
            : new JsonResponse($this->image->get($id), 400);
    }
}
