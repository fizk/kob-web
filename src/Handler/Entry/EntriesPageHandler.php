<?php declare(strict_types=1);

namespace App\Handler\Entry;

use App\Template\TemplateRendererInterface;
use App\Service\EntryService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

class EntriesPageHandler implements RequestHandlerInterface
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
        return new HtmlResponse(
            $this->template->render('app::entries-page', [
                'list' => $this->entry->fetchList($request->getAttribute('year')),
                'year' => $request->getAttribute('year')
            ])
        );
    }
}
