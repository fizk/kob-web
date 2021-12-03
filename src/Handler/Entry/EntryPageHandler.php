<?php declare(strict_types=1);

namespace App\Handler\Entry;

use App\Template\TemplateRendererInterface;
use App\Service\EntryService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

class EntryPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface$template;
    private EntryService $entry;

    public function __construct(EntryService $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->entry->fetch(
            $this->extractId($request->getAttribute('id')),
            $request->getAttribute('language', 'is')
        );
        return $entry
            ? new HtmlResponse($this->template->render('app::entry-page', ['entry' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }

    private function extractId(string $slug)
    {
        $result = [];
        preg_match('/[0-9]*$/', $slug, $result);
        return $result[0];
    }
}
