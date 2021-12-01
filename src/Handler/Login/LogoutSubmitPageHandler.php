<?php declare(strict_types=1);

namespace App\Handler\Login;

use App\Router\RouterInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use Laminas\Authentication\AuthenticationServiceInterface;

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
