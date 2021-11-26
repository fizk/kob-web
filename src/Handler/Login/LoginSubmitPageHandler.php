<?php declare(strict_types=1);

namespace App\Handler\Login;

use App\Auth\PasswordAuthAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use Laminas\Authentication\AuthenticationServiceInterface;
use App\Router\RouterInterface;

class LoginSubmitPageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private AuthenticationServiceInterface $authService;
    private PasswordAuthAdapter $authAdapter;

    public function __construct(
        RouterInterface $router,
        AuthenticationServiceInterface $authService,
        PasswordAuthAdapter $authAdapter
    ) {
        $this->router      = $router;
        $this->authService = $authService;
        $this->authAdapter = $authAdapter;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $body = $request->getParsedBody();
        $this->authAdapter->setCredentials($body['email'], $body['password']);
        $result = $this->authService->authenticate();

        if ($result->isValid()) {
            return new RedirectResponse($this->router->generateUri('update'));
        } else {
            return new RedirectResponse($this->router->generateUri('login'));
        }
    }
}
