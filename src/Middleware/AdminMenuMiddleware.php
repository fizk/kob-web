<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Template\TemplateRendererInterface;
use App\Service\Manifesto;

class AdminMenuMiddleware implements MiddlewareInterface
{
    private TemplateRendererInterface $templateRenderer;
    private Manifesto $manifesto;

    public function __construct(TemplateRendererInterface $template, Manifesto $manifesto)
    {
        $this->templateRenderer = $template;
        $this->manifesto = $manifesto;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'admin_menu_pages',
            $this->manifesto->fetch()
        );

        return $handler->handle($request);
    }
}
