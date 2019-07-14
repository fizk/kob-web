<?php

declare(strict_types=1);

namespace App\Handler;

use App\Auth\SimpleAuthAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Router;

class LogoutSubmitPageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var \Zend\Authentication\AuthenticationService */
    private $authService;

    /** @var \App\Auth\SimpleAuthAdapter */
    private $authAdapter;

    public function __construct(
        Router\RouterInterface $router,
        AuthenticationService $authService,
        SimpleAuthAdapter $authAdapter
    ) {
        $this->router      = $router;
        $this->authService = $authService;
        $this->authAdapter = $authAdapter;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->authService->clearIdentity();

        return new RedirectResponse($this->router->generateUri('home'));

    }
}
