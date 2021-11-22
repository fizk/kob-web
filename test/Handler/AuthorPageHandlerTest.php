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

class AuthorPageHandlerTest extends TestCase
{
    public function testFetchAuthorIcelandic()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/listamenn/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\Author::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function fetch(string $id): ?Model\Author
                {
                    return (new Model\Author())
                        ->setId(1)
                        ->setName('name1');
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFetchAuthorEnglish()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/authors/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\Author::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function fetch(string $id): ?Model\Author
                {
                    return (new Model\Author())
                        ->setId(1)
                        ->setName('name1');
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFetchAuthorNotFound()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/listamenn/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\Author::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function fetch(string $id): ?Model\Author
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
