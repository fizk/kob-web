<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service;
use Elasticsearch\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class SearchPageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Search */
    private $search;

    public function __construct(
        Router\RouterInterface $router,
        Service\Search $search,
        TemplateRendererInterface $template
    ) {
        $this->router   = $router;
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
