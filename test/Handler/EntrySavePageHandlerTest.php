<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{RedirectResponse, HtmlResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use Laminas\Dom\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Router\RouterInterface;
use App\Middleware;
use App\Service;
use App\Model;
use Exception;

use function App\Router\dispatch;

class EntrySavePageHandlerTest extends TestCase
{
    public function testSaveAndRedirect()
    {
        $request = (new ServerRequest())
            ->withParsedBody([
                'title' => 'Some title',
                'from' => '2001-01-01',
                'to' => '2001-01-01',
                'type' => 'show',
                'orientation' => 'landscape',
                'authors' => [1,2,3],
                'gallery' => [1,2,3],
                'posters' => [1,2,3],
            ])
            ->withUri(new Uri('/update/entry'))
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
        $serviceManager->setFactory(Service\EntryService::class, function () {
            return new class extends Service\AbstractEntry{
                public function save(Model\Entry $entry): int
                {
                    $gallery = $entry->getGallery();
                    array_walk($gallery, function(Model\Image $image) {
                        $id = $image->getId();
                        if (!$id) throw new Exception();
                    });
                    $posters = $entry->getPosters();
                    array_walk($posters, function(Model\Image $image) {
                        $id = $image->getId();
                        if (!$id) throw new Exception();
                    });
                    $authors = $entry->getAuthors();
                    array_walk($authors, function(Model\Author $author) {
                        $id = $author->getId();
                        if (!$id) throw new Exception();
                    });

                    return 2;
                }
                public function get(string $id): ?Model\Entry
                {
                    return new Model\Entry();
                }
            };
        });
        $serviceManager->setFactory(Service\SearchService::class, function () {
            return new class extends Service\AbstractSearch {
                public function save($item): bool
                {
                    return true;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['/syningar/2'], $response->getHeader('location'));
    }

    public function testSaveError()
    {
        $request = (new ServerRequest())
            ->withParsedBody([
                'title' => null,
                'from' => '2001-01-01',
                'to' => '2001-01-01',
                'type' => 'show',
                'orientation' => 'landscape',
            ])
            ->withUri(new Uri('/update/entry'))
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
        $serviceManager->setFactory(Service\EntryService::class, function () {
            return new class extends Service\AbstractEntry
            {
                public function save(Model\Entry $entry): int
                {
                    $i = 0;
                    return 2;
                }
                public function get(string $id): ?Model\Entry
                {
                    return new Model\Entry();
                }
            };
        });
        $serviceManager->setFactory(Service\SearchService::class, function () {
            return new class extends Service\AbstractSearch
            {
                public function save($item): bool
                {
                    return true;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertCount(1, (new Query($response->getBody()->__toString()))->execute('.form-error'));
    }
}
