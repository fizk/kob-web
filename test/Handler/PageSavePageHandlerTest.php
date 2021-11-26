<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\RedirectResponse;
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

class PageSavePageHandlerTest extends TestCase
{
    public function testSaveAndRedirect()
    {
        $request = (new ServerRequest())
            ->withParsedBody([
                'type' => 'sometype',
                'body_is' => '',
                'body_en' => '',
            ])
            ->withUri(new Uri('/update/manifesto/1'))
            ->withMethod('POST');

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setFactory(Middleware\AdminMenuMiddleware::class, function () {
            return new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
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
        $serviceManager->setFactory(Service\PageService::class, function () {
            return new class extends Service\AbstracPage {};
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['/about'], $response->getHeader('location'));
    }
}
