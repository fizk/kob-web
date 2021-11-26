<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service\AbstractEntry;
use function App\Router\dispatch;

class EntriesPageHandlerTest extends TestCase
{
    public function testFetchEntriesIcelandic()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/syningar'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\EntryService::class, function () {
            return new class extends AbstractEntry{};
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFetchEntriesEnglish()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/shows'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\EntryService::class, function () {
            return new class extends AbstractEntry{};
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
