<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{JsonResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use App\Router\RouterInterface;
use App\Service;
use function App\Router\dispatch;

class AuthorsSearchPageHandlerTest extends TestCase
{
    public function testSearchForAuthor()
    {
        $request = (new ServerRequest())
            ->withQueryParams(['q' => 'search'])
            ->withUri(new Uri('/api/author/search'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\Author::class, function () {
            return new class extends Service\AbstractAuthor{};
        });

        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
