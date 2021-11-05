<?php declare(strict_types=1);

namespace App\Handler\Page;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{RedirectResponse};
use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use App\Service\Manifesto;
use App\Form\ManifestoForm;

class ManifestoSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private TemplateRendererInterface $template;
    private Manifesto $manifesto;

    public function __construct(RouterInterface $router, TemplateRendererInterface $template, Manifesto $manifesto)
    {
        $this->router    = $router;
        $this->template = $template;
        $this->manifesto = $manifesto;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        $post = $request->getParsedBody();

        $form = new ManifestoForm();
        $form->setData(array_merge($post, ['id' => $id]));

        if ($form->isValid()) {
            $this->manifesto->save($form->getData());
            $this->manifesto->attachImages($id, isset($post['gallery']) ? $post['gallery'] : []);

            return new RedirectResponse($this->router->generateUri('about'));
        }

    }
}
