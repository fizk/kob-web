<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Service;

class EntryPageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Entry */
    private $entry;

    public function __construct(
        Router\RouterInterface $router,
        Service\Entry $entry,
        ?TemplateRendererInterface $template = null
    ) {
        $this->router   = $router;
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->entry->fetch($request->getAttribute('id'));
        return $entry
            ? new HtmlResponse($this->template->render('app::entry-page', ['entry' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);

    }
}
