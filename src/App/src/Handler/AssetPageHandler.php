<?php

declare(strict_types=1);

namespace App\Handler;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Stream;
class AssetPageHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $size = $request->getAttribute('size', '10x10');
        $name = $request->getAttribute('name', '10x10');


        if (!file_exists(__DIR__ . "/../../../../public/img/{$size}/{$name}")){

            if (!file_exists('./data/images/' . $name)) {
                return new EmptyResponse(404);
            }

            if (!is_dir(__DIR__ . "/../../../../public/img/{$size}")) {
                mkdir(__DIR__ . "/../../../../public/img/{$size}");
            }

            $dimensions = $this->parseRequestedDimensions($size);

            $imagine = new Imagine();
            $image = $imagine->open('./data/images/' . $name);

            $height = $image->getSize()->getHeight();
            $width = $image->getSize()->getWidth();

            if ($dimensions['width'] === null && $dimensions['height'] === null) {
                $image->save(
                        __DIR__ . '/../../../../public/img/' . $size. '/'. $name
                    );
            } elseif ($dimensions['width'] === null || $dimensions['height'] === null) {
                $resize = $dimensions['width'] === null
                    ? $image->getSize()->scale($dimensions['height'] / $height)
                    : $image->getSize()->scale($dimensions['width'] / $width );
                $image->resize($resize)
                    ->save(
                        __DIR__ . '/../../../../public/img/' . $size. '/'. $name
                    );

            } else {
                $image->thumbnail(
                    new Box($dimensions['width'], $dimensions['height']),
                    ImageInterface::THUMBNAIL_OUTBOUND
                )->save(
                    __DIR__ . '/../../../../public/img/' . $size. '/'. $name
                );
            }

        }

        $stream = new Stream(
            __DIR__ . '/../../../../public/img/' . $size. '/'. $name,
            'r'
        );

        return new TextResponse($stream, 200, [
            'Content-Type' => 'image/jpg',
            'Content-Length' => $stream->getSize(),
            'Cache-Control' => 'public, max-age=31536000',
            'ETag' => '1536-58e3fc84b1180',
            'Accept-Ranges' => 'bytes',
            'Last-Modified' => 'Thu, 14 Apr 2003 04:22:50 GMT',
            'Expires' => 'Thu, 14 Apr 2033 04:22:50 GMT'
        ]);

    }

    private function parseRequestedDimensions(string $size)
    {
        $sizeResult = [];
        preg_match('/([0-9]*)x([0-9]*)/', $size, $sizeResult);

        return [
            'width' => is_numeric($sizeResult[1]) ? (int) $sizeResult[1] : null,
            'height' => is_numeric($sizeResult[2]) ? (int) $sizeResult[2] : null,
        ];

    }
}
