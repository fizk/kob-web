<?php
namespace App\Middleware;

use App\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DetectLanguageMiddleware implements MiddlewareInterface
{
    private $templateRenderer;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->templateRenderer = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $languageHeaders = $request->getHeader('x-language-set');
        $language = isset($languageHeaders[0]) ? $languageHeaders[0] : 'is';
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'language',
            $language
        );
        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'primary_language',
            $language === 'is'
        );

        return $handler->handle($request->withAttribute('language', $language));
    }
}
