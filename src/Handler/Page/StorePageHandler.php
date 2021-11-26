<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\StoreService;

class StorePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private StoreService $store;

    public function __construct(StoreService $store, TemplateRendererInterface $template)
    {
        $this->store = $store;
        $this->template  = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $list = $this->store->fetchAll($request->getAttribute('language', 'is'));
        return new HtmlResponse($this->template->render('app::store-page', ['list' => $list]));
    }
}
