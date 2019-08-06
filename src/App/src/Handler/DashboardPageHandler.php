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

class DashboardPageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Entry */
    private $entry;

    /** @var \App\Service\Author */
    private $author;

    public function __construct(
        Router\RouterInterface $router,
        Service\Entry $entry,
        Service\Author $author,
        TemplateRendererInterface $template
    ) {
        $this->router   = $router;
        $this->entry    = $entry;
        $this->author   = $author;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('app::dashboard-page', [
                'entries' => $this->entry->fetchAffected(),
                'authors' => $this->author->fetchAffected(),
            ])
        );
    }
}
