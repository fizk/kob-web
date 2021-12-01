<?php declare(strict_types=1);

namespace App\Handler\User;

use App\Router\RouterInterface;
use App\Service\UserService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};

class UsersDeletePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private UserService $user;

    public function __construct(UserService $user, RouterInterface $router)
    {
        $this->user    = $user;
        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $this->user->delete($request->getAttribute('id'));
        return new RedirectResponse($this->router->generateUri('create-user'));
    }
}
