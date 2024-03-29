<?php declare(strict_types=1);

namespace App\Handler\Page;

use App\Template\TemplateRendererInterface;
use App\Service\PageService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

class PageUpdatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private PageService $page;

    public function __construct(PageService $page, TemplateRendererInterface $template)
    {
        $this->page    = $page;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        return new HtmlResponse(
            $this->template->render('dashboard::page-update-page', [
                'page' => $this->page->get($id)
            ])
        );
    }
}
