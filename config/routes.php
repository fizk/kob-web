<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use App\Handler;
use App\Middleware;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', Handler\HomePageHandler::class, 'home');
    $app->get('/list/:year', Handler\EntriesPageHandler::class, 'list');
    $app->get('/items', Handler\EntriesPageHandler::class, 'entries');
    $app->get('/items/:id', Handler\EntryPageHandler::class, 'entry');
    $app->get('/authors', Handler\AuthorsPageHandler::class, 'authors');
    $app->get('/about', Handler\ManifestoPageHandler::class, 'about');
    $app->get('/login', Handler\LoginPageHandler::class, 'login');
    $app->post('/login', Handler\LoginSubmitPageHandler::class, 'login-submit');
    $app->get('/logout', Handler\LogoutSubmitPageHandler::class, 'logout-submit');

    $app->post('/image', Handler\ImageSavePageHandler::class, 'images-save');
    $app->get('/api/author/search', Handler\AuthorsSearchPageHandler::class, 'author-search');

    $app->get('/update', [
        Middleware\AuthenticationMiddleware::class,
        Handler\DashboardPageHandler::class
    ], 'update');
    $app->get('/update/entry', [
        Middleware\AuthenticationMiddleware::class,
        Handler\EntryCreatePageHandler::class
    ], 'create-entry');
    $app->get('/update/entry/:id', [
        Middleware\AuthenticationMiddleware::class,
        Handler\EntryUpdatePageHandler::class
    ], 'update-entry');
    $app->get('/delete/entry/:id', [
        Middleware\AuthenticationMiddleware::class,
        Handler\EntryDeletePageHandler::class
    ], 'delete-entry');
    $app->post('/update/entry/:id', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\EntrySavePageHandler::class
    ], 'save-entry');
    $app->post('/update/entry', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\EntrySavePageHandler::class
    ], 'new-entry');

    $app->get('/update/author/:id', [
        Middleware\AuthenticationMiddleware::class,
        Handler\AuthorUpdatePageHandler::class
    ], 'update-author');
    $app->post('/update/author/:id', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\AuthorSavePageHandler::class
    ], 'save-author');
    $app->get('/update/author', [
        Middleware\AuthenticationMiddleware::class,
        Handler\AuthorCreatePageHandler::class
    ], 'create-author');
    $app->post('/update/author', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\AuthorSavePageHandler::class
    ], 'new-author');
    $app->get('/delete/author/:id', [
        Middleware\AuthenticationMiddleware::class,
        Handler\AuthorDeletePageHandler::class
    ], 'delete-author');

    $app->get('/update/manifesto', [
        Middleware\AuthenticationMiddleware::class,
        Handler\ManifestoUpdatePageHandler::class
    ], 'update-manifesto');
    $app->post('/update/manifesto', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\ManifestoSavePageHandler::class
    ], 'new-manifesto');

    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
};
