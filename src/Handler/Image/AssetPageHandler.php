<?php declare(strict_types=1);

namespace App\Handler\Image;

use App\Service\AssetService;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\{TextResponse, EmptyResponse};
use Laminas\Diactoros\Stream;

class AssetPageHandler implements RequestHandlerInterface
{
    private AssetService $asset;

    public function __construct(AssetService $asset)
    {
        $this->asset = $asset;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $size = $request->getAttribute('size', '10x10');
        $name = $request->getAttribute('name', '10x10');

        $resource = $this->asset->get($size, $name);

        if (!$resource) {
            return new EmptyResponse(404);
        }

        $stream = new Stream($resource);
        return new TextResponse($stream, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Length' => $stream->getSize(),
            'Cache-Control' => 'public',
            'Accept-Ranges' => 'bytes',
            'Last-Modified' => (new \DateTime())->format('D, j M Y H:i:s \G\M\T'),
            'Expires' => (new \DateTime())->add(new \DateInterval('P1Y'))->format('D, j M Y H:i:s \G\M\T')
        ]);
    }
}
