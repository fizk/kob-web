<?php declare(strict_types=1);

namespace App\Handler\Image;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{JsonResponse};
use App\Router\RouterInterface;
use App\Service\{Asset, Image};

class ImageSavePageHandler implements RequestHandlerInterface
{
    private RouterInterface $router;
    private Image $image;
    private Asset $asset;

    public function __construct(RouterInterface $router, Image $image, Asset $asset)
    {
        $this->router   = $router;
        $this->image    = $image;
        $this->asset    = $asset;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @todo safe filename
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $files = $request->getUploadedFiles();

        $result = array_map(function ($file) {
            $response = $this->asset->save($file);

            $fileMetadate = [
                'name' => $response['name'],
                'description' => null,
                'height' => $response['height'],
                'width' => $response['width'],
                'created' => (new \DateTime())->format('Y-m-d H:i:s'),
                'affected' => (new \DateTime())->format('Y-m-d H:i:s'),
            ];

            $id = $this->image->save($fileMetadate);

            return array_merge($fileMetadate, [
                'id' => $id,
                'size' => $file->getSize(),
                'thumb' => $this->router->generateUri('asset', [
                    'name' => $response['name'],
                    'size' => '100x100'
                ]),
            ]);
        }, $files);

        return (new JsonResponse(array_values($result), 201));
    }
}
