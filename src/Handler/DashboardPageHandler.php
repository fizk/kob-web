<?php declare(strict_types=1);

namespace App\Handler;

use App\Template\TemplateRendererInterface;
use App\Service\{EntryService, AuthorService};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

class DashboardPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private EntryService $entry;
    private AuthorService $author;

    public function __construct(EntryService $entry, AuthorService $author, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->author   = $author;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('dashboard::dashboard-page', [
                'entries' => $this->entry->fetchAffected(),
                'authors' => $this->author->fetchAffected(),
            ])
        );
    }
}
