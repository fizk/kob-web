<?php declare(strict_types=1);

namespace App\Handler\Author;

use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use App\Service\AuthorService;
use App\Form\AuthorForm;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\{RedirectResponse, JsonResponse, HtmlResponse};

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
        $withHeaders = $request->getHeader('X-REQUESTED-WITH');
        $language = $request->getAttribute('language', 'is');
        $data = $request->getParsedBody();

        $form = new AuthorForm();
        $form->setData($data);

        if ($form->isValid()) {
            $model = $form->getModel();
            $createdId = $this->author->save($model);

            return in_array('xmlhttprequest', $withHeaders)
                ? new JsonResponse(['id' => $createdId,'name' => $model->getName(),])
                : new RedirectResponse($this->router->generateUri(
                    $language == 'is' ? 'listamadur' : 'author',
                    ['id' => $createdId]
                ));
        }

        return in_array('xmlhttprequest', $withHeaders)
            ? new JsonResponse(['messages' => $form->getMessages(),], 400)
            : new HtmlResponse($this->template->render('dashboard::author-update-page', [
                'author' => $data,
                'messages' => $form->getMessages()
            ]), 400);
    }
}
