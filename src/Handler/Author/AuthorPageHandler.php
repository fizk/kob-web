<?php

namespace App\Handler\Author;

use App\Service\AuthorService;
use App\Template\TemplateRendererInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\HtmlResponse;

class AuthorPageHandler implements RequestHandlerInterface
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
        $entry = $this->author->fetch($this->extractId($request->getAttribute('id')));
        return $entry
            ? new HtmlResponse($this->template->render('app::author-page', ['author' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }

    private function extractId(string $slug)
    {
        $result = [];
        preg_match('/[0-9]*$/', $slug, $result);
        return $result[0];
    }
}
