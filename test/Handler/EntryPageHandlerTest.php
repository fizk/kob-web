<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service\AbstractEntry;
use App\Model;
use function App\Router\dispatch;

class EntryPageHandlerTest extends TestCase
{
    public function testGetEntrySuccessIcelandic()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/syningar/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\EntryService::class, function () {
            return new class extends AbstractEntry
            {
                public function fetch(string $id, $lang = 'is'): Model\Entries
                {
                    return (new Model\Entries());
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetEntrySuccessEnglish()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/shows/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\EntryService::class, function () {
            return new class extends AbstractEntry
            {
                public function fetch(string $id, $lang = 'is'): Model\Entries
                {
                    return new Model\Entries();
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetEntryNotFoundIcelandic()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/syningar/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\EntryService::class, function () {
            return new class extends AbstractEntry {
                public function fetch(string $id, string $lang = 'is'): ?Model\Entries
                {
                    return null;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testGetEntryNotFoundEnglish()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/shows/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(\App\Service\EntryService::class, function () {
            return new class extends AbstractEntry {
                public function fetch(string $id, string $lang = 'is'): ?Model\Entries
                {
                    return null;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
