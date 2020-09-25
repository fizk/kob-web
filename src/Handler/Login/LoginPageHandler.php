<?php declare(strict_types=1);

namespace App\Handler\Login;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;

class LoginPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->template      = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('app::login-page')
        );
    }
}
