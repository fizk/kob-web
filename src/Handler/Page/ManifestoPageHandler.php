<?php declare(strict_types=1);

namespace App\Handler\Page;

use App\Router\RouterInterface;
use App\Service\PageService;
use App\Template\TemplateRendererInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};

class ManifestoPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private PageService $page;

    public function __construct(RouterInterface $router, PageService $page, TemplateRendererInterface $template)
    {
        $this->router    = $router;
        $this->page = $page;
        $this->template  = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->page->getByType('manifesto', $request->getAttribute('language', 'is'));

        return $entry
            ? new HtmlResponse($this->template->render('app::manifesto-page', ['manifesto' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
