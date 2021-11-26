<?php declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse};
use App\Template\TemplateRendererInterface;
use App\Service\SearchService;

class SearchPageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $template;
    private SearchService $search;

    public function __construct(SearchService $search, TemplateRendererInterface $template)
    {
        $this->search    = $search;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $language = $request->getAttribute('language', 'is');
        $query = $request->getQueryParams();
        if (array_key_exists('q', $query)) {
            $response = $this->search->search($query['q'], $language);
            return new HtmlResponse($this->template->render('app::search-page', $response));
        } else {
            return new HtmlResponse($this->template->render('app::search-page', [
                'count' => 0,
                'results' => [],
                'query' => ''
            ]));
        }
    }
}
