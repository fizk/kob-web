<?php

declare(strict_types=1);

namespace App\Handler;

use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Service;


class ImageSavePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /** @var \App\Service\Image */
    private $image;

    public function __construct(
        Router\RouterInterface $router,
        Service\Image $image,
        TemplateRendererInterface $template
    ) {
        $this->router   = $router;
        $this->image    = $image;
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @todo safe filename
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $files = $request->getUploadedFiles();

        $result = [];

        foreach ($files as $key => $value) {
            $prefix = rand(100000, 999999);
            /** @var $value \Zend\Diactoros\UploadedFile */
            $name = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value->getClientFilename());
            $value->moveTo('./data/images/' . $prefix . $name);

            $imagine = new Imagine();
            $image = $imagine->open('./data/images/' . $prefix . $name);

            $height = $image->getSize()->getHeight();
            $width = $image->getSize()->getWidth();

            $id = $this->image->save([
                'name' => $prefix . $name,
                'description' => null,
                'height' => $height,
                'width' => $width,
                'created' => (new \DateTime())->format('Y-m-d H:i:s'),
                'affected' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);

            $result[] = [
                'id' => $id,
                'key' => $key,
                'name' => $prefix . $name,
                'size' => $value->getSize(),
                'height' => $height,
                'width' => $width,
                'thumb' => $this->router->generateUri('asset', [
                    'name' => $prefix . $name,
                    'size' => '100x100'
                ])
            ];
        }

        return (new JsonResponse($result));
    }
}
