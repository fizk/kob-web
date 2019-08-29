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

class AuthorPageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Author */
    private $author;

    public function __construct(
        Router\RouterInterface $router,
        Service\Author $author,
        TemplateRendererInterface $template
    ) {
        $this->router   = $router;
        $this->author   = $author;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];
        preg_match('/[0-9]*$/', $request->getAttribute('id'), $result);
        $entry = $this->author->fetch($result[0]);

        return $entry
            ? new HtmlResponse($this->template->render('app::author-page', ['author' => $entry]))
            : new HtmlResponse($this->template->render('error::404'), 404);
    }
}