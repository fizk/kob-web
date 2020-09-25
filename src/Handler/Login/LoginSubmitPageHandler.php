<?php declare(strict_types=1);

namespace App\Handler\Login;

use App\Auth\SimpleAuthAdapter;
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
    private SimpleAuthAdapter $authAdapter;

    public function __construct(
        RouterInterface $router,
        AuthenticationServiceInterface $authService,
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
