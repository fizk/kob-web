<?php

namespace App\Handler\Login;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use function App\Router\dispatch;

class LoginPageHandlerTest extends TestCase
{
    public function testFetchManifestoIcelandic()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/login'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
