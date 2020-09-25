<?php declare(strict_types=1);

namespace App\Handler\Author;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Author;

class AuthorUpdatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Author $author;

    public function __construct(Author $author, TemplateRendererInterface $template)
    {
        $this->author   = $author;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        //@todo author not found
        return new HtmlResponse(
            $this->template->render('app::author-update-page', [
                'author' => $this->author->get($request->getAttribute('id'))
            ])
        );
    }
}
