<?php declare(strict_types=1);

namespace App\Handler\Image;

use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{JsonResponse};
use App\Service\Image;

class ImageUpdatePageHandler implements RequestHandlerInterface
{
    private Image $image;

    public function __construct(Image $image)
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
