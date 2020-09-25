<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Router\RouterInterface;
use App\Middleware;
use App\Service;

use function App\Router\dispatch;

class AuthorUpdatePageHandlerTest extends TestCase
{
    public function test200OK()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/update/author/1'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(Service\Author::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function get(string $id): \stdClass
                {
                    return (object)['id' => 1];
                }
            };
        });
        $serviceManager->setFactory(Middleware\AuthenticationMiddleware::class, function () {
            return new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            };
        });
        $serviceManager->setFactory(Service\Manifesto::class, function () {
            return new class extends Service\AbstracManifesto {};
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
