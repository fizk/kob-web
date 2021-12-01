<?php declare(strict_types=1);

namespace App\Handler\User;

use App\Template\TemplateRendererInterface;
use App\Service\UserService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

class UsersPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private UserService $user;

    public function __construct(UserService $user, TemplateRendererInterface $template)
    {
        $this->user    = $user;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('dashboard::users-page', ['users' => $this->user->fetch()])
        );
    }
}
