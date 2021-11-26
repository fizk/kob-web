<?php declare(strict_types=1);

namespace App\Handler\Image;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{JsonResponse};
use App\Service\ImageService;

class ImageUpdatePageHandler implements RequestHandlerInterface
{
    private ImageService $image;

    public function __construct(ImageService $image)
    {
        $this->image    = $image;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', null);
        $post = $request->getParsedBody();

        $affectedRows = $this->image->updateDescription($id, $post['description'], new \DateTime());

        return $affectedRows > 0
            ? new JsonResponse($this->image->get($id))
            : new JsonResponse($this->image->get($id), 400);
    }
}
