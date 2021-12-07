<?php declare(strict_types=1);

namespace App\Handler\Page;

use App\Template\TemplateRendererInterface;
use App\Service\PageService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

class SupportersPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private PageService $page;

    public function __construct(PageService $page, TemplateRendererInterface $template)
    {
        $this->page = $page;
        $this->template  = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->page->getByType('supporters', $request->getAttribute('language', 'is'));

        return $entry
            ? new HtmlResponse($this->template->render('app::supporters-page', ['page' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
