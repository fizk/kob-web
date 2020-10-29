<?php declare(strict_types=1);

namespace App\Handler\Login;

use App\Auth\FacebookAuthAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use Laminas\Authentication\AuthenticationServiceInterface;
use App\Router\RouterInterface;

class FbLoginSubmitPageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private AuthenticationServiceInterface $authService;
    private FacebookAuthAdapter $authAdapter;

    public function __construct(
        RouterInterface $router,
        AuthenticationServiceInterface $authService,
        FacebookAuthAdapter $authAdapter
    ) {
        $this->router      = $router;
        $this->authService = $authService;
        $this->authAdapter = $authAdapter;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $params = (array) $request->getQueryParams();
        $this->authAdapter->setCode($params['code']);
        $result = $this->authService->authenticate();

        if ($result->isValid()) {
            return new RedirectResponse($this->router->generateUri('update'));
        } else {
            return new RedirectResponse($this->router->generateUri('login'));
        }
    }
}
