<?php

namespace App\Handler;

use App\Router\RouterInterface;
use App\Service\AbstractEntry;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use function App\Router\dispatch;

class HomeHandlerTest extends TestCase
{
    public function test200OK()
    {
        $request = (new ServerRequest())->withUri(new Uri('/'));
        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\Entry::class, function () {
            return new class extends AbstractEntry {};
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
