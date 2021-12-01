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
        $result = [];
        preg_match('/[0-9]*$/', $request->getAttribute('id'), $result);
        $entry = $this->entry->fetch($result[0], $request->getAttribute('language', 'is'));
        return $entry
            ? new HtmlResponse($this->template->render('app::entry-page', ['entry' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
