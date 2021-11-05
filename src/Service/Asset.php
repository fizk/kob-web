<?php

namespace App\Service;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Psr\Http\Message\UploadedFileInterface;

class Asset
{
    private string $cache;
    private string $htdocs;

    public function __construct(string $cache = './data/images/', string $htdocs = 'html')
    {
        $this->cache = $cache;
        $this->htdocs = $htdocs;
    }

    /**
     * @return resource|null
     */
    public function get(string $size, string $name) /* : ?resource */
    {
        if (!file_exists($this->cache . $name)) {
            $dimensions = $this->parseRequestedDimensions($size);
            $imagine = new Imagine();
            $img = $imagine->create(new Box($dimensions['width'] ?: 100, $dimensions['height'] ?: 100));
            $pointer = fopen('php://temp', 'r+');
            fputs($pointer, $img->get('jpeg'));
            return $pointer;
        }

        if (!file_exists(sprintf('./%s/img/%s/%s', $this->htdocs, $size, $name))) {
            if (!is_dir(sprintf('./%s/img/%s', $this->htdocs, $size))) {
                mkdir(sprintf('./%s/img/%s', $this->htdocs, $size), 0777, true);
            }

            $dimensions = $this->parseRequestedDimensions($size);

            $imagine = new Imagine();
            $image = $imagine->open($this->cache . $name);

            $height = $image->getSize()->getHeight();
            $width = $image->getSize()->getWidth();

            if ($dimensions['max'] === true) {
                if ($height <= $dimensions['height'] && $width <= $dimensions['width']) {
                    $image->save(sprintf('./%s/img/%s/%s', $this->htdocs, $size, $name));
                } else {
                    $scale = ($dimensions['height'] / $height * $width) >= $dimensions['height']
                        ? $dimensions['height'] / $height
                        : $dimensions['width'] / $width;

                    $image->resize($image->getSize()->scale($scale))
                        ->save(sprintf('./%s/img/%s/%s', $this->htdocs, $size, $name));
                }
            } elseif ($dimensions['width'] === null && $dimensions['height'] === null) {
                $image->save(sprintf('./%s/img/%s/%s', $this->htdocs, $size, $name));
            } elseif ($dimensions['width'] === null || $dimensions['height'] === null) {
                $resize = $dimensions['width'] === null
                    ? $image->getSize()->scale($dimensions['height'] / $height)
                    : $image->getSize()->scale($dimensions['width'] / $width);
                $image->resize($resize)
                    ->save(sprintf('./%s/img/%s/%s', $this->htdocs, $size, $name));
            } else {
                $image->thumbnail(
                    new Box($dimensions['width'], $dimensions['height']),
                    ImageInterface::THUMBNAIL_OUTBOUND
                )->save(sprintf('./%s/img/%s/%s', $this->htdocs, $size, $name));
            }
        }

        return fopen(sprintf('./%s/img/%s/%s', $this->htdocs, $size, $name), 'r');
    }

    public function save(UploadedFileInterface $value): array
    {
        if (!is_dir($this->cache)) {
            mkdir($this->cache);
        }

        $name = $this->constructFileName($value->getClientFilename());

        $handle = fopen('php://temp', 'wb+');
        $stream = $value->getStream();
        $stream->rewind();
        while (!$stream->eof()) {
            fwrite($handle, $stream->read(4096));
        }
        fseek($handle, 0);

        $imagine = new Imagine();
        $sizes = $imagine->read($handle)
            ->thumbnail(new Box(2560, 1600), ImageInterface::THUMBNAIL_INSET, ImageInterface::FILTER_LANCZOS)
            ->save($this->cache . $name, ['jpeg_quality' => 90])
            ->getSize();

        fclose($handle);

        return [
            'name' => $name,
            'height' => $sizes->getHeight(),
            'width' => $sizes->getWidth(),
        ];
    }

    private function parseRequestedDimensions(string $size): array
    {
        $sizeResult = [];
        preg_match('/([0-9]*)x([0-9]*)(max)?/', $size, $sizeResult);

        return [
            'width' => is_numeric($sizeResult[1]) ? (int) $sizeResult[1] : null,
            'height' => is_numeric($sizeResult[2]) ? (int) $sizeResult[2] : null,
            'max' => isset($sizeResult[3]) && $sizeResult[3] === 'max'
        ];
    }

    private function constructFileName(string $filename, string $extension = 'jpg'): string
    {
        $prefix = rand(100000, 999999);

        $name = $prefix . str_replace(
            ['?', '/', '#', ' '],
            '',
            filter_var($filename, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)
        );

        return preg_replace('/\.[a-z]{3,4}$/', '', $name) . ".{$extension}";
    }
}
