<?php

namespace App\Handler\Author;

use App\Service\AuthorService;
use App\Template\TemplateRendererInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\HtmlResponse;

class AuthorUpdatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private AuthorService $author;

    public function __construct(AuthorService $author, TemplateRendererInterface $template)
    {
        $this->author   = $author;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $author = $this->author->get($request->getAttribute('id'));
        return $author
            ? new HtmlResponse($this->template->render('dashboard::author-update-page', ['author' => $author]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
