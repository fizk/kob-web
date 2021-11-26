<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Template\TemplateRendererInterface;
use App\Service\PageService;

class AdminMenuMiddleware implements MiddlewareInterface
{
    private TemplateRendererInterface $templateRenderer;
    private PageService $page;

    public function __construct(TemplateRendererInterface $template, PageService $page)
    {
        $this->templateRenderer = $template;
        $this->page = $page;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'admin_menu_pages',
            $this->page->fetch()
        );

        return $handler->handle($request);
    }
}
