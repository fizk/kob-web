<?php declare(strict_types=1);

namespace App\Handler\Entry;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Entry;

class ProjectsPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private $entry;

    public function __construct(Entry $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('app::projects-page', [
                'list' => $this->entry->fetchByType('proj'),
            ])
        );
    }
}
