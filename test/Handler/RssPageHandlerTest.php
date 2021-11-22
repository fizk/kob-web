<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\XmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service\AbstractEntry;
use function App\Router\dispatch;

class RssPageHandlerTest extends TestCase
{
    public function testFetchEntriesIcelandic()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/rss'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\Entry::class, function () {
            return new class extends AbstractEntry {
                    public function fetchFeed(): array {
                        return [];
                    }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(XmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
