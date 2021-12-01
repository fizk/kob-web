<?php
namespace App\Middleware;

use App\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SecondaryLanguageMiddleware implements MiddlewareInterface
{
    private TemplateRendererInterface $templateRenderer;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->templateRenderer = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'language',
            'en'
        );

        return $handler->handle($request->withAttribute('language', 'en'));
    }
}
