<?php declare(strict_types=1);

namespace App\Handler\Login;

use App\Auth\SimpleAuthAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use Laminas\Authentication\AuthenticationServiceInterface;
use App\Router\RouterInterface;

class LogoutSubmitPageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private AuthenticationServiceInterface $authService;

    public function __construct(
        RouterInterface $router,
        AuthenticationServiceInterface $authService
    ) {
        $this->router      = $router;
        $this->authService = $authService;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->authService->clearIdentity();

        return new RedirectResponse($this->router->generateUri('home'));
    }
}
