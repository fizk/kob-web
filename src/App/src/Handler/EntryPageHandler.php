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
        TemplateRendererInterface $template
    ) {
        $this->router   = $router;
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];
        preg_match('/[0-9]*$/', $request->getAttribute('id'), $result);
        $entry = $this->entry->fetch($result[0], $request->getAttribute('language', 'is'));
        return $entry
            ? new HtmlResponse($this->template->render('app::entry-page', ['entry' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);

    }
}
