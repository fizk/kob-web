<?php
namespace App\Middleware;

use App\Service;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AdminMenuMiddleware implements MiddlewareInterface
{
    /** @var TemplateRendererInterface */
    private $templateRenderer;

    /** @var \App\Service\Manifesto */
    private $manifesto;

    public function __construct(TemplateRendererInterface $template, Service\Manifesto $manifesto)
    {
        $this->templateRenderer = $template;
        $this->manifesto = $manifesto;
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
            'admin_menu_pages',
            $this->manifesto->fetch()
        );

        return $handler->handle($request);
    }
}
