<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{JsonResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service;
use App\Middleware;
use Laminas\Diactoros\UploadedFile;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function App\Router\dispatch;

class ImageSavePageHandlerTest extends TestCase
{
    public function testGetSuccess()
    {
        $request = (new ServerRequest([], [
            new UploadedFile(fopen('php://memory', 'r+'), 0, 0)
        ]))->withUri(new Uri('/image'))
            ->withMethod('POST');

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\Image::class, function () {
            return new class extends Service\AbstractImage {
                public function save(array $data): int
                {
                    return 1;
                }
            };
        });
        $serviceManager->setFactory(Service\Asset::class, function () {
            return new class extends Service\AbstracAsset
            {
                public function save(UploadedFileInterface $value): array
                {
                    return [
                        'name' => '$name',
                        'height' => 0,
                        'width' => 0,
                    ];
                }
            };
        });
        $serviceManager->setFactory(Middleware\AuthenticationMiddleware::class, function () {
            return new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
