<?php declare(strict_types=1);

namespace App\Handler\Page;

use App\Router\RouterInterface;
use App\Service\PageService;
use App\Form\PageForm;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};

class PageSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private PageService $page;

    public function __construct(RouterInterface $router, PageService $page)
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
            $this->page->save($form->getModel());
            $this->page->attachImages($id, isset($post['gallery']) ? $post['gallery'] : []);

            return new RedirectResponse($this->router->generateUri('about'));
        }
    }
}
