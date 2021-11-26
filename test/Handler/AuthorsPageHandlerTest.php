<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service;
use function App\Router\dispatch;

class AuthorsPageHandlerTest extends TestCase
{
    public function testFetchListIcelandic()
    {
        $request = (new ServerRequest())->withUri(new Uri('/listamenn'));
        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\AuthorService::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function fetchList(): array
                {
                    return [];
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFetchListEnglish()
    {
        $request = (new ServerRequest())->withUri(new Uri('/authors'));
        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\AuthorService::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function fetchList(): array
                {
                    return [];
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
