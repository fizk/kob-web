<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Template\TemplateRendererInterface;
use App\Service\Entry;

class MenuMiddleware implements MiddlewareInterface
{
    private TemplateRendererInterface $templateRenderer;
    private Entry $entry;

    public function __construct(TemplateRendererInterface $template, Entry $entry)
    {
        $this->templateRenderer = $template;
        $this->entry = $entry;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'entry_years',
            $this->entry->fetchYears()
        );

        return $handler->handle($request);
    }
}
