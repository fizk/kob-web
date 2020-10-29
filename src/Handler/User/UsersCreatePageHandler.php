<?php declare(strict_types=1);

namespace App\Handler\User;

use App\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Service\User;

class UsersCreatePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private User $user;

    public function __construct(User $user, RouterInterface $router)
    {
        $this->user    = $user;
        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {

        $this->user->save([
            'email' => $request->getParsedBody()['email'],
            'name' => $request->getParsedBody()['email'],
            'id' => md5((string)time())
        ]);
        return new RedirectResponse($this->router->generateUri('create-user'));
    }
}
