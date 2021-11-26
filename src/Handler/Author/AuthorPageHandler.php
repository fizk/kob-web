<?php declare(strict_types=1);

namespace App\Handler\Author;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use App\Template\TemplateRendererInterface;
use App\Service\AuthorService;

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
        $result = [];
        preg_match('/[0-9]*$/', $request->getAttribute('id'), $result);
        $entry = $this->author->fetch($result[0]);

        return $entry
            ? new HtmlResponse($this->template->render('app::author-page', ['author' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
