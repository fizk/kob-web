<?php declare(strict_types=1);

namespace App\Handler\Store;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse, RedirectResponse};
use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use App\Service\{Store};
use App\Form\{StoreForm};
use DateTime;

class StoreSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private TemplateRendererInterface $template;
    private Store $store;

    public function __construct(
        RouterInterface $router,
        TemplateRendererInterface $template,
        Store $store,
    ) {
        $this->router = $router;
        $this->template = $template;
        $this->store  = $store;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();

        $data = array_merge($post, [
            'id' => $id,
            'affected' => (new DateTime())->format('Y-m-d H:i:s')
        ], $id ? [] : ['created' => (new DateTime())->format('Y-m-d H:i:s'),]);

        $form = new StoreForm();
        $form->setData($data);

        if ($form->isValid()) {

            $insertedId = $this->store->save($form->getData());
        //     $this->entry->attachAuthors((string) $insertedId, isset($post['author']) ? $post['author'] : []);
        //     $this->entry->attachImages((string) $insertedId, isset($post['gallery']) ? $post['gallery'] : [], 2);

            return new RedirectResponse($this->router->generateUri('verslun'));
        }

        return new HtmlResponse($this->template->render('dashboard::store-update-page', [
            'entry' => $data,
            'messages' => $form->getMessages(),
        ]), 400);
    }
}
