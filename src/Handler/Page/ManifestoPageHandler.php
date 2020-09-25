<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Router\RouterInterface;
use App\Service\Manifesto;

class ManifestoPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Manifesto $manifesto;

    public function __construct(RouterInterface $router, Manifesto $manifesto, TemplateRendererInterface $template)
    {
        $this->router    = $router;
        $this->manifesto = $manifesto;
        $this->template  = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->manifesto->getByType('manifesto', $request->getAttribute('language', 'is'));

        return $entry
            ? new HtmlResponse($this->template->render('app::manifesto-page', ['manifesto' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}
