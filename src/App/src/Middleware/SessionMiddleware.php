<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class SessionMiddleware implements MiddlewareInterface
{
    /** @var TemplateRendererInterface */
    private $templateRenderer;

    /** @var \Zend\Authentication\AuthenticationServiceInterface */
    private $authentication;

    public function __construct(TemplateRendererInterface $template, AuthenticationServiceInterface $authentication)
    {
        $this->templateRenderer = $template;
        $this->authentication = $authentication;
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
            'user_session',
            $this->authentication->hasIdentity() ? $this->authentication->getIdentity() : false
        );

        return $handler->handle($request);
    }
}
