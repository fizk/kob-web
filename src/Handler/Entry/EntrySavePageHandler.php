<?php declare(strict_types=1);

namespace App\Handler\Entry;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{HtmlResponse, RedirectResponse};
use App\Router\RouterInterface;
use App\Template\TemplateRendererInterface;
use App\Service\{EntryService, SearchService};
use App\Form\{EntryForm};
use DateTime;

class EntrySavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private TemplateRendererInterface $template;
    private EntryService $entry;
    private SearchService $search;

    public function __construct(
        RouterInterface $router,
        TemplateRendererInterface $template,
        EntryService $entry,
        SearchService $search
    ) {
        $this->router = $router;
        $this->template = $template;
        $this->entry  = $entry;
        $this->search = $search;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = $request->getParsedBody();
        $language = $request->getAttribute('language', 'is');

        $form = new EntryForm();
        $form->setData($data);

        if ($form->isValid()) {
            $insertedId = $this->entry->save($form->getModel());
            $this->search->save(
                $this->entry->get((string) $insertedId)
            );

            return new RedirectResponse($this->router->generateUri(
                $language == 'is' ? 'syning' : 'entry',
                ['id' => $insertedId]
            ));
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
