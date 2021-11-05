<?php declare(strict_types=1);

namespace App\Handler\Author;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse, JsonResponse, HtmlResponse};
use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use App\Service\Author;
use App\Form\AuthorForm;
use DateTime;

class AuthorSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private TemplateRendererInterface $template;
    private Author $author;

    public function __construct(RouterInterface $router, TemplateRendererInterface $template, Author $author)
    {
        $this->router = $router;
        $this->template = $template;
        $this->author  = $author;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $withHeaders = $request->getHeader('X-REQUESTED-WITH');
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();

        $data = array_merge(
            $post,
            ['id' => $id, 'affected' => (new DateTime())->format('Y-m-d H:i:s')],
            $id ? [] : ['created' => (new DateTime())->format('Y-m-d H:i:s'),]
        );

        $form = new AuthorForm();
        $form->setData($data);

        if ($form->isValid()) {
            $createdId = $this->author->save($form->getData());

            return in_array('xmlhttprequest', $withHeaders)
                ? new JsonResponse(['id' => $createdId,'name' => $post['name'],])
                : new RedirectResponse($this->router->generateUri('author', ['id' => $createdId]));
        }

        return in_array('xmlhttprequest', $withHeaders)
            ? new JsonResponse(['messages' => $form->getMessages(),], 400)
            : new HtmlResponse($this->template->render('dashboard::author-update-page', [
                'author' => $data,
                'messages' => $form->getMessages()
            ]), 400);
    }
}
