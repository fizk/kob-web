<?php declare(strict_types=1);

namespace App\Handler\Entry;

use App\Model\Entry;
use App\Template\TemplateRendererInterface;
use App\Service\EntryService;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\HtmlResponse;

class ProjectsPageHandler implements RequestHandlerInterface
{
    private EntryService $entry;
    private TemplateRendererInterface $template;

    public function __construct(EntryService $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('app::projects-page', [
                'list' => $this->entry->fetchByType(Entry::PROJECT),
            ])
        );
    }
}
