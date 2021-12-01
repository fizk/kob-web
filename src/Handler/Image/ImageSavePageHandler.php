<?php declare(strict_types=1);

namespace App\Handler\Image;

use App\Model\Image;
use App\Service\{AssetService, ImageService};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{JsonResponse};
use DateTime;

class ImageSavePageHandler implements RequestHandlerInterface
{
    private ImageService $image;
    private AssetService $asset;

    public function __construct(ImageService $image, AssetService $asset)
    {
        $this->image    = $image;
        $this->asset    = $asset;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $files = $request->getUploadedFiles();

        $result = array_map(function ($file) {
            $response = $this->asset->save($file);

            $image = (new Image())
                ->setName($response['name'])
                ->setDescription(null)
                ->setHeight($response['height'])
                ->setWidth($response['width'])
                ->setSize($file->getSize())
                ->setCreated(new DateTime())
                ->setAffected(new DateTime())
                ;

            $id = $this->image->save($image);

            return $image->setId($id);
        }, $files);

        return (new JsonResponse(array_values($result), 201));
    }
}
