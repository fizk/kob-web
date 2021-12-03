<?php

namespace App\Handler\Author;

use App\Form\AuthorForm;
use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use App\Service\AuthorService;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\{RedirectResponse, HtmlResponse};

class AuthorSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private TemplateRendererInterface $template;
    private AuthorService $author;

    public function __construct(RouterInterface $router, TemplateRendererInterface $template, AuthorService $author)
    {
        $this->router = $router;
        $this->template = $template;
        $this->author  = $author;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = $request->getParsedBody();
        $form = (new AuthorForm())->setData($data);

        if ($form->isValid()) {
            $model = $form->getModel();
            $createdId = $this->author->save($model);

            return new RedirectResponse($this->router->generateUri(
                $request->getAttribute('language', 'is') == 'is' ? 'listamadur' : 'author',
                ['id' => $createdId]
            ));
        }

        return new HtmlResponse($this->template->render('dashboard::author-update-page', [
            'author' => $data,
            'messages' => $form->getMessages()
        ]), 400);
    }
}
