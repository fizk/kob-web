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
    $app->get('/', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\HomePageHandler::class
    ], 'heim');
    $app->get('/home', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\HomePageHandler::class
    ], 'home');

    $app->get('/listi/:year', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\EntriesPageHandler::class
    ], 'listi');
    $app->get('/list/:year', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\EntriesPageHandler::class
    ], 'list');

    $app->get('/verkefni', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\ProjectsPageHandler::class
    ], 'verkefni');
    $app->get('/projects', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\ProjectsPageHandler::class
    ], 'projects');

    $app->get('/syningar', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\EntriesPageHandler::class
    ], 'syningar');
    $app->get('/shows', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\EntriesPageHandler::class
    ], 'entries');

    $app->get('/syningar/:id', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\EntryPageHandler::class
    ], 'syning');
    $app->get('/shows/:id', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\EntryPageHandler::class
    ], 'entry');

    $app->get('/listamenn', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\AuthorsPageHandler::class
    ], 'listamenn');
    $app->get('/authors', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\AuthorsPageHandler::class
    ], 'authors');

    $app->get('/listamenn/:id', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\AuthorPageHandler::class
    ], 'listamadur');
    $app->get('/authors/:id', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\AuthorPageHandler::class
    ], 'author');

    $app->get('/um', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\ManifestoPageHandler::class
    ], 'um');
    $app->get('/about', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\ManifestoPageHandler::class
    ], 'about');

    $app->get('/verslun', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\StorePageHandler::class
    ], 'verslun');
    $app->get('/store', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\StorePageHandler::class
    ], 'store');

    $app->get('/leit', [
        Middleware\PrimaryLanguageMiddleware::class,
        Handler\SearchPageHandler::class
    ], 'leit');
    $app->get('/search', [
        Middleware\SecondaryLanguageMiddleware::class,
        Handler\SearchPageHandler::class
    ], 'search');

    $app->get('/rss', [
        Handler\RssPageHandler::class
    ], 'rss');

    $app->get('/login', Handler\LoginPageHandler::class, 'login');
    $app->post('/login', Handler\LoginSubmitPageHandler::class, 'login-submit');
    $app->get('/logout', Handler\LogoutSubmitPageHandler::class, 'logout-submit');

    $app->get('/img/:size/:name', Handler\AssetPageHandler::class, 'asset');
    $app->post('/image', Handler\ImageSavePageHandler::class, 'images-save');
    $app->get('/api/author/search', Handler\AuthorsSearchPageHandler::class, 'author-search');

    $app->get('/update', [
        Middleware\AuthenticationMiddleware::class,
        Middleware\AdminMenuMiddleware::class,
        Handler\DashboardPageHandler::class
    ], 'update');
    $app->get('/update/entry', [
        Middleware\AuthenticationMiddleware::class,
        Middleware\AdminMenuMiddleware::class,
        Handler\EntryCreatePageHandler::class
    ], 'create-entry');
    $app->get('/update/entry/:id', [
        Middleware\AuthenticationMiddleware::class,
        Middleware\AdminMenuMiddleware::class,
        Handler\EntryUpdatePageHandler::class
    ], 'update-entry');
    $app->get('/delete/entry/:id', [
        Middleware\AuthenticationMiddleware::class,
        Middleware\AdminMenuMiddleware::class,
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
        Middleware\AdminMenuMiddleware::class,
        Handler\AuthorUpdatePageHandler::class
    ], 'update-author');
    $app->post('/update/author/:id', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\AuthorSavePageHandler::class
    ], 'save-author');
    $app->get('/update/author', [
        Middleware\AuthenticationMiddleware::class,
        Middleware\AdminMenuMiddleware::class,
        Handler\AuthorCreatePageHandler::class
    ], 'create-author');
    $app->post('/update/author', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\AuthorSavePageHandler::class
    ], 'new-author');
    $app->get('/delete/author/:id', [
        Middleware\AuthenticationMiddleware::class,
        Middleware\AdminMenuMiddleware::class,
        Handler\AuthorDeletePageHandler::class
    ], 'delete-author');
    $app->get('/update/manifesto/:id', [
        Middleware\AuthenticationMiddleware::class,
        Middleware\AdminMenuMiddleware::class,
        Handler\ManifestoUpdatePageHandler::class
    ], 'update-manifesto');
    $app->post('/update/manifesto/:id', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\ManifestoSavePageHandler::class
    ], 'new-manifesto');
    $app->post('/update/image/:id', [
        Middleware\AuthenticationMiddleware::class,
        BodyParamsMiddleware::class,
        Handler\ImageUpdatePageHandler::class
    ], 'update-image');

};
