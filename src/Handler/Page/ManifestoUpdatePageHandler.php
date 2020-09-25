<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Manifesto;

class ManifestoUpdatePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Manifesto $manifesto;

    public function __construct(Manifesto $manifesto, TemplateRendererInterface $template)
    {
        $this->manifesto    = $manifesto;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        return new HtmlResponse(
            $this->template->render('app::manifesto-update-page', [
                'manifesto' => $this->manifesto->get($id)
            ])
        );
    }
}
