<?php declare(strict_types=1);

namespace App\Handler\Store;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\StoreService;

class StoreUpdatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private StoreService $store;

    public function __construct(StoreService $store, TemplateRendererInterface $template)
    {
        $this->store    = $store;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->store->fetch((int)$request->getAttribute('id'));
        return $entry
            ? new HtmlResponse($this->template->render('dashboard::store-update-page', ['entry' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
