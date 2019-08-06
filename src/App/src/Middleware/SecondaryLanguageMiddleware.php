<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Service;

class SecondaryLanguageMiddleware implements MiddlewareInterface
{
    /** @var TemplateRendererInterface */
    private $templateRenderer;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->templateRenderer = $template;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
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
