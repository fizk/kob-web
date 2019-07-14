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

class ManifestoUpdatePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Manifesto */
    private $manifesto;

    public function __construct(
        Router\RouterInterface $router,
        Service\Manifesto $manifesto,
        ?TemplateRendererInterface $template = null
    ) {
        $this->router   = $router;
        $this->manifesto    = $manifesto;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = [
            'manifesto' => $this->manifesto->get()
        ];

        return new HtmlResponse($this->template->render('app::manifesto-update-page', $data));
    }
}
