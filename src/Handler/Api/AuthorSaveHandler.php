<?php declare(strict_types=1);

namespace App\Handler\Api;

use App\Service\AuthorService;
use App\Form\AuthorForm;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Laminas\Diactoros\Response\JsonResponse;

class AuthorSaveHandler implements RequestHandlerInterface
{
    private AuthorService $author;

    public function __construct(AuthorService $author)
    {
        $this->author  = $author;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $language = $request->getAttribute('language', 'is');
        $data = $request->getParsedBody();

        $form = (new AuthorForm())->setData($data);

        if ($form->isValid()) {
            $model = $form->getModel();
            $createdId = $this->author->save($model);

            return new JsonResponse([
                'id' => $createdId,
                'name' => $model->getName(),
            ]);
        }

        return new JsonResponse(['messages' => $form->getMessages(),], 400);
    }
}
