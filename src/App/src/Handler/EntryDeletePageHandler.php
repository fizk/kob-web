<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Service;

class EntryDeletePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var \App\Service\Entry */
    private $entry;

    public function __construct(Router\RouterInterface $router, Service\Entry $entry) {
        $this->router = $router;
        $this->entry  = $entry;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->entry->delete($request->getAttribute('id'));

        return new RedirectResponse($this->router->generateUri('update'));
    }
}
