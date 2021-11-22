<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Page;

class PageUpdatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Page $page;

    public function __construct(Page $page, TemplateRendererInterface $template)
    {
        $this->page    = $page;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        return new HtmlResponse(
            $this->template->render('dashboard::manifesto-update-page', [
                'manifesto' => $this->page->get($id)
            ])
        );
    }
}
