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
        ?TemplateRendererInterface $template = null
    ) {
        $this->router   = $router;
        $this->image    = $image;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $files = $request->getUploadedFiles();

        $result = [];

        foreach ($files as $key => $value) {
            $prefix = rand(100000, 999999);
            /** @var $value \Zend\Diactoros\UploadedFile */
            $value->moveTo('./public/img/raw/' . $prefix . $value->getClientFilename());
            $imagine = new Imagine();

            $image = $imagine->open('./public/img/raw/' . $prefix . $value->getClientFilename());
            $height = $image->getSize()->getHeight();
            $width = $image->getSize()->getWidth();

            $resize = $width > 1280
                ? $image->getSize()->scale(1280 / $width)
                : $image->getSize()->scale(1);

            $image->thumbnail(new Box(100, 100), ImageInterface::THUMBNAIL_OUTBOUND)
                ->save('./public/img/thumb/' . $prefix. $value->getClientFilename());

            $image->resize($resize)
                ->save('./public/img/original/' . $prefix . $value->getClientFilename());

            $id = $this->image->save([
                'name' => $value->getClientFilename(),
                'description' => null,
                'created' => (new \DateTime())->format('Y-m-d H:i:s'),
                'affected' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);

            $result[] = [
                'id' => $id,
                'key' => $key,
                'name' => $prefix . $value->getClientFilename(),
                'size' => $value->getSize(),
                'height' => $height,
                'width' => $width,
            ];
        }

        return (new JsonResponse($result));
    }
}
