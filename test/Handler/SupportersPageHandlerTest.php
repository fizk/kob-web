<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service;
use App\Model;

use function App\Router\dispatch;

class SupportersPageHandlerTest extends TestCase
{
    public function testFetchStoreIcelandic()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/vinir'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\PageService::class, function () {
            return new class extends Service\AbstracPage {
                public function getByType($type, $lang = 'is'): ?Model\Page
                {
                    return new Model\Page();
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFetchStoreEnglish()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/friends'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\PageService::class, function () {
            return new class extends Service\AbstracPage {
                public function getByType($type, $lang = 'is'): ?Model\Page
                {
                    return new Model\Page();
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
