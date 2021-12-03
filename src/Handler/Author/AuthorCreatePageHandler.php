<?php

namespace App\Handler\Author;

use App\Template\TemplateRendererInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\HtmlResponse;

class AuthorCreatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('dashboard::author-update-page', ['author' => []])
        );
    }
}
