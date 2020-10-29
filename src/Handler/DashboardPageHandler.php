<?php declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\{Entry, Author};

class DashboardPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Entry $entry;
    private Author $author;

    public function __construct(Entry $entry, Author $author, TemplateRendererInterface $template)
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
