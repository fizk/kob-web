<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{TextResponse, EmptyResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service;
use function App\Router\dispatch;

class AssetPageHandlerTest extends TestCase
{
    public function testGetSuccess()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/img/100x100/name'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\AssetService::class, function () {
            return new class extends Service\AbstracAsset
            {
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(TextResponse::class, $response);
        $this->assertEquals(['image/jpeg'], $response->getHeader('Content-Type'));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetNotFound()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/img/100x100/name'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\AssetService::class, function () {
            return new class extends Service\AbstracAsset
            {
                public function get(string $size, string $name)
                {
                    return false;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(EmptyResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
