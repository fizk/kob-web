<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthenticationMiddleware implements MiddlewareInterface
{
    private AuthenticationServiceInterface $authentication;

    public function __construct(AuthenticationServiceInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->authentication->hasIdentity()
            ? $handler->handle($request)
            : new RedirectResponse('/login');
    }
}
