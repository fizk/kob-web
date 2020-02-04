<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Service;

class InventoryUpdatePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Inventory */
    private $inventory;

    public function __construct(
        Router\RouterInterface $router,
        Service\Inventory $inventory,
        TemplateRendererInterface $template
    ) {
        $this->router   = $router;
        $this->inventory= $inventory;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        return new HtmlResponse(
            $this->template->render('app::inventory-update-page', [
                'item' => $this->inventory->fetch($id)
            ])
        );
    }
}
