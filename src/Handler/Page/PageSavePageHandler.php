<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Router\RouterInterface;
use App\Service\Page;
use App\Form\PageForm;

class PageSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private Page $page;

    public function __construct(RouterInterface $router, Page $page)
    {
        $this->router    = $router;
        $this->page = $page;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        $post = $request->getParsedBody();

        $form = new PageForm();
        $form->setData(array_merge($post, ['id' => $id]));

        if ($form->isValid()) {
            $this->page->save($form->getData());
            $this->page->attachImages($id, isset($post['gallery']) ? $post['gallery'] : []);

            return new RedirectResponse($this->router->generateUri('about'));
        }
    }
}
