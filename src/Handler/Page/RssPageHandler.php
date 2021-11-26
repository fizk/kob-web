<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{XmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\EntryService;

class RssPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private EntryService $entry;

    public function __construct(EntryService $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new XmlResponse(
            $this->template->render('app::rss-page', [
                'list' => $this->entry->fetchFeed(),
            ])
        );
    }
}
