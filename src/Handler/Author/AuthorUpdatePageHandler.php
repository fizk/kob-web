<?php declare(strict_types=1);

namespace App\Handler\Author;

use App\Template\TemplateRendererInterface;
use App\Service\AuthorService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

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
        //@todo author not found
        return new HtmlResponse(
            $this->template->render('dashboard::author-update-page', [
                'author' => $this->author->get($request->getAttribute('id'))
            ])
        );
    }
}
