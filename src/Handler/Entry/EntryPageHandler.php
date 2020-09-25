<?php declare(strict_types=1);

namespace App\Handler\Entry;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Entry;

class EntryPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface$template;
    private Entry $entry;

    public function __construct(Entry $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // print_r($request);
        $result = [];
        preg_match('/[0-9]*$/', $request->getAttribute('id'), $result);
        $entry = $this->entry->fetch($result[0], $request->getAttribute('language', 'is'));
        return $entry
            ? new HtmlResponse($this->template->render('app::entry-page', ['entry' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
