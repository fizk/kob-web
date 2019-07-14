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

class LoginSubmitPageHandler implements RequestHandlerInterface
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
        $post = $request->getParsedBody();
        $this->authAdapter->setUsername($post['username']);
        $this->authAdapter->setPassword($post['password']);

        $result = $this->authService->authenticate();

        if ($result->isValid()) {
            return new RedirectResponse($this->router->generateUri('home'));
        } else {
            return new RedirectResponse($this->router->generateUri('login'));
        }

    }
}
