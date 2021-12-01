<?php
namespace App\Middleware;

use App\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Authentication\AuthenticationServiceInterface;

class SessionMiddleware implements MiddlewareInterface
{
    private TemplateRendererInterface $templateRenderer;
    private AuthenticationServiceInterface $authentication;

    public function __construct(TemplateRendererInterface $template, AuthenticationServiceInterface $authentication)
    {
        $this->templateRenderer = $template;
        $this->authentication = $authentication;
    }

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
