<?php declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\Entry;
use DateTime;

class HomePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private Entry $entry;

    public function __construct(Entry $entry, TemplateRendererInterface $template)
    {
        $this->entry    = $entry;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $current = $this->entry->fetchByDate(new DateTime(), $request->getAttribute('language', 'is'));
        $upcoming = $this->entry->fetchAfter(new DateTime(), $request->getAttribute('language', 'is'));

        $list = empty($current) ? $upcoming : $current;
        $next = empty($current) ? [] : $upcoming;
        // $fallback = empty($list) && empty($next) ? $this->entry->fetchList((new DateTime())->format('Y')) : null;
        $fallback = empty($list) && empty($next) ? $this->entry->fetchList() : null;

        return new HtmlResponse(
            $this->template->render('app::home-page', [
                'list' => [], //$fallback ? : $list,
                'upcoming' => $next,
            ])
        );
    }
}
