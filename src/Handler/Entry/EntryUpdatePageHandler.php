<?php declare(strict_types=1);

namespace App\Handler\Entry;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Entry;

class EntryUpdatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Entry $entry;

    public function __construct(Entry $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->entry->get($request->getAttribute('id'));
        return $entry
            ? new HtmlResponse($this->template->render('dashboard::entry-update-page', ['entry' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
