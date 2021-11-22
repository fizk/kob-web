<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Page;

class SupportersPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Page $page;

    public function __construct(Page $page, TemplateRendererInterface $template)
    {
        $this->page = $page;
        $this->template  = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->page->getByType('supporters', $request->getAttribute('language', 'is'));

        return $entry
            ? new HtmlResponse($this->template->render('app::supporters-page', ['manifesto' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
