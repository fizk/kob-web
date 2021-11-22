<?php declare(strict_types=1);

namespace App\Handler\Entry;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse, RedirectResponse};
use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use App\Service\{Entry, Search};
use App\Form\{EntryForm};
use DateTime;

class EntrySavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private TemplateRendererInterface $template;
    private Entry $entry;
    private Search $search;

    public function __construct(
        RouterInterface $router,
        TemplateRendererInterface $template,
        Entry $entry,
        Search $search
    ) {
        $this->router = $router;
        $this->template = $template;
        $this->entry  = $entry;
        $this->search = $search;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();


        $data = array_merge($post, [
            'id' => $id,
            'affected' => (new DateTime())->format('Y-m-d H:i:s')
        ], $id ? [] : ['created' => (new DateTime())->format('Y-m-d H:i:s'),]);

        $form = new EntryForm();
        $form->setData($data);

        if ($form->isValid()) {
            $insertedId = $this->entry->save($form->getData());
            $this->entry->attachAuthors((string) $insertedId, isset($post['author']) ? $post['author'] : []);
            $this->entry->attachImages((string) $insertedId, isset($post['poster']) ? $post['poster'] : [], 1);
            $this->entry->attachImages((string) $insertedId, isset($post['gallery']) ? $post['gallery'] : [], 2);

            $entry = $this->entry->get((string) $insertedId);

            $this->search->save($entry);

            return new RedirectResponse($this->router->generateUri('entry', ['id' => $insertedId]));
        }

        //FIXME
        $data['from'] = new DateTime($data['from']);
        $data['to'] = new DateTime($data['to']);
        return new HtmlResponse($this->template->render('dashboard::entry-update-page', [
            'entry' => $data,
            'messages' => $form->getMessages(),
        ]), 400);
    }
}
