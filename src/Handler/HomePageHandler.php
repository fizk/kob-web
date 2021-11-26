<?php declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\EntryService;
use DateTime;

class HomePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private EntryService $entry;

    public function __construct(EntryService $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $language = $request->getAttribute('language', 'is');

        $list = $this->entry->fetchCurrent(new DateTime(), $language);
        $list = count($list) > 0
            ? $list
            : $this->entry->fetchLatestByType(EntryService::PROJECT, $language);
        $next = $this->entry->fetchAfter(new DateTime(), $language);

        return new HtmlResponse(
            $this->template->render('app::home-page', [
                'list' => $list,
                'upcoming' => $next,
            ])
        );
    }
}
