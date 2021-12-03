<?php
namespace App\Middleware;

use App\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PrimaryLanguageMiddleware implements MiddlewareInterface
{
    private $templateRenderer;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->templateRenderer = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'language',
            'is'
        );
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'primary_language',
            true
        );

        return $handler->handle($request->withAttribute('language', 'is'));
    }
}
