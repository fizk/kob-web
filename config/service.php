<?php

use Psr\Container\ContainerInterface;
use App\Auth\PasswordAuthAdapter;
use App\Auth\FacebookAuthAdapter;
use App\Service;
use App\Handler;
use App\Middleware;
use App\Filters;
use App\Filters\ParesDownAdapter;
use App\Router\RouterInterface;
use App\Router\RouteCollection;
use App\Template\TwigRenderer;
use App\Template\TemplateRendererInterface;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\AuthenticationServiceInterface;
use Aptoma\Twig\Extension\MarkdownExtension;

return [
    'factories'  => [
        Handler\Image\AssetPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Image\AssetPageHandler(
                $container->get(Service\AssetService::class)
            );
        },
        Handler\Image\ImageSavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Image\ImageSavePageHandler(
                $container->get(Service\ImageService::class),
                $container->get(Service\AssetService::class),
            );
        },
        Handler\Image\ImageUpdatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Image\ImageUpdatePageHandler(
                $container->get(Service\ImageService::class)
            );
        },
        Handler\HomePageHandler::class => function (ContainerInterface $container) {
            return new Handler\HomePageHandler(
                $container->get(Service\EntryService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\EntryPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntryPageHandler(
                $container->get(Service\EntryService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\EntryUpdatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntryUpdatePageHandler(
                $container->get(Service\EntryService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\EntryCreatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntryCreatePageHandler(
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\EntrySavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntrySavePageHandler(
                $container->get(RouterInterface::class),
                $container->get(TemplateRendererInterface::class),
                $container->get(Service\EntryService::class),
                $container->get(Service\SearchService::class)
            );
        },
        Handler\Entry\EntryDeletePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntryDeletePageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\EntryService::class)
            );
        },
        Handler\Entry\EntriesPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntriesPageHandler(
                $container->get(Service\EntryService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\ProjectsPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\ProjectsPageHandler(
                $container->get(Service\EntryService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Store\StoreUpdatePageHandler::class => function(ContainerInterface $container) {
            return (new Handler\Store\StoreUpdatePageHandler(
                $container->get(Service\StoreService::class),
                $container->get(TemplateRendererInterface::class)
            ));
        },
        Handler\Author\AuthorsPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorsPageHandler(
                $container->get(Service\AuthorService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Author\AuthorPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorPageHandler(
                $container->get(Service\AuthorService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Api\AuthorsSearchHandler::class => function (ContainerInterface $container) {
            return new Handler\Api\AuthorsSearchHandler (
                $container->get(Service\AuthorService::class)
            );
        },
        Handler\Page\ManifestoPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\ManifestoPageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\PageService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Store\StorePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Store\StorePageHandler(
                $container->get(Service\StoreService::class),
                $container->get(Service\PageService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Store\StoreSavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Store\StoreSavePageHandler(
                $container->get(RouterInterface::class),
                $container->get(TemplateRendererInterface::class),
                $container->get(Service\StoreService::class)
            );
        },
        Handler\Store\StoreCreatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Store\StoreCreatePageHandler(
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Store\StoreDeletePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Store\StoreDeletePageHandler (
                $container->get(RouterInterface::class),
                $container->get(Service\StoreService::class)
            );
        },
        Handler\Page\SupportersPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\SupportersPageHandler(
                $container->get(Service\PageService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\DashboardPageHandler::class => function (ContainerInterface $container) {
            return new Handler\DashboardPageHandler(
                $container->get(Service\EntryService::class),
                $container->get(Service\AuthorService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Login\LoginPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Login\LoginPageHandler(
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Login\LoginSubmitPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Login\LoginSubmitPageHandler(
                $container->get(RouterInterface::class),
                $container->get(AuthenticationServiceInterface::class)->setAdapter(
                    $container->get(PasswordAuthAdapter::class)
                ),
                $container->get(PasswordAuthAdapter::class),
            );
        },
        Handler\Login\FbLoginSubmitPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Login\FbLoginSubmitPageHandler(
                $container->get(RouterInterface::class),
                $container->get(AuthenticationServiceInterface::class)->setAdapter(
                    $container->get(FacebookAuthAdapter::class)
                ),
                $container->get(FacebookAuthAdapter::class)
            );
        },
        Handler\Login\LogoutSubmitPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Login\LogoutSubmitPageHandler(
                $container->get(RouterInterface::class),
                $container->get(AuthenticationServiceInterface::class)
            );
        },
        Handler\Author\AuthorUpdatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorUpdatePageHandler(
                $container->get(Service\AuthorService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Author\AuthorSavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorSavePageHandler(
                $container->get(RouterInterface::class),
                $container->get(TemplateRendererInterface::class),
                $container->get(Service\AuthorService::class)
            );
        },
        Handler\Author\AuthorCreatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorCreatePageHandler(
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Author\AuthorDeletePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorDeletePageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\AuthorService::class)
            );
        },
        Handler\User\UsersPageHandler::class => function (ContainerInterface $container) {
            return new Handler\User\UsersPageHandler(
                $container->get(Service\UserService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\User\UsersCreatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\User\UsersCreatePageHandler(
                $container->get(Service\UserService::class),
                $container->get(RouterInterface::class),
            );
        },
        Handler\User\UsersDeletePageHandler::class => function (ContainerInterface $container) {
            return new Handler\User\UsersDeletePageHandler(
                $container->get(Service\UserService::class),
                $container->get(RouterInterface::class),
            );
        },

        Handler\Page\PageSavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\PageSavePageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\PageService::class)
            );
        },
        Handler\Page\PageUpdatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\PageUpdatePageHandler(
                $container->get(Service\PageService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\SearchPageHandler::class => function (ContainerInterface $container) {
            return new Handler\SearchPageHandler(
                $container->get(Service\SearchService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\ApiSearchPageHandler::class => function (ContainerInterface $container) {
            return new Handler\ApiSearchPageHandler(
                $container->get(Service\SearchService::class)
            );
        },
        Handler\Page\RssPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\RssPageHandler(
                $container->get(Service\EntryService::class),
                $container->get(TemplateRendererInterface::class)
            );
        },



        Handler\Api\AuthorSaveHandler::class => function (ContainerInterface $container) {
            return new Handler\Api\AuthorSaveHandler (
                $container->get(Service\AuthorService::class)
            );
        },


        Middleware\DetectLanguageMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\DetectLanguageMiddleware(
                $container->get(TemplateRendererInterface::class)
            );
        },
        Middleware\SecondaryLanguageMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\SecondaryLanguageMiddleware(
                $container->get(TemplateRendererInterface::class)
            );
        },
        Middleware\PrimaryLanguageMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\PrimaryLanguageMiddleware(
                $container->get(TemplateRendererInterface::class)
            );
        },
        Middleware\SessionMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\SessionMiddleware(
                $container->get(TemplateRendererInterface::class),
                $container->get(AuthenticationServiceInterface::class)
            );
        },
        Middleware\MenuMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\MenuMiddleware(
                $container->get(TemplateRendererInterface::class),
                $container->get(Service\EntryService::class)
            );
        },
        Middleware\AuthenticationMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\AuthenticationMiddleware(
                $container->get(AuthenticationServiceInterface::class)
            );
        },
        Middleware\AdminMenuMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\AdminMenuMiddleware(
                $container->get(TemplateRendererInterface::class),
                $container->get(Service\PageService::class)
            );
        },
        Middleware\AdditionalDatesMiddleware::class => function (ContainerInterface $container) {
            return new Middleware\AdditionalDatesMiddleware();
        },

        PasswordAuthAdapter::class => function (ContainerInterface $container) {
            return new PasswordAuthAdapter(
                $container->get(Service\UserService::class)
            );
        },
        FacebookAuthAdapter::class => function (ContainerInterface $container) {
            return new FacebookAuthAdapter(
                'https://graph.facebook.com/v8.0',
                $container->get(Service\UserService::class),
                getenv('FB_ID') ?: '2085720918322296',
                getenv('FB_SECRET') ?: '813a22630cace0901074dd8ad5188cb8',
                getenv('FB_REDIRECT') ?: 'http://localhost/login'
            );
        },
        AuthenticationServiceInterface::class => function (ContainerInterface $container) {
            return new AuthenticationService();
        },
        Mezzio\Helper\BodyParams\BodyParamsMiddleware::class => function () {
            return new Mezzio\Helper\BodyParams\BodyParamsMiddleware();
        },

        Service\EntryService::class => function (ContainerInterface $container) {
            return new Service\EntryService($container->get(PDO::class));
        },
        Service\StoreService::class => function (ContainerInterface $container) {
            return new Service\StoreService($container->get(PDO::class));
        },
        Service\UserService::class => function (ContainerInterface $container) {
            return new Service\UserService($container->get(PDO::class));
        },
        Service\AuthorService::class => function (ContainerInterface $container) {
            return new Service\AuthorService($container->get(PDO::class));
        },
        Service\PageService::class => function (ContainerInterface $container) {
            return new Service\PageService($container->get(PDO::class));
        },
        Service\ImageService::class => function (ContainerInterface $container) {
            return new Service\ImageService($container->get(PDO::class));
        },
        Service\SearchService::class => function (ContainerInterface $container) {
            return new Service\SearchService($container->get(Client::class));
        },
        Service\AssetService::class => function (ContainerInterface $container) {
            return new Service\AssetService('./image-cache/');
        },

        PDO::class => function (ContainerInterface $container) {
            $host = getenv('DB_HOST') ?: 'database';
            $port = getenv('DB_PORT') ?: 3306;
            $name = getenv('DB_NAME') ?: 'klingogbang';
            $user = getenv('DB_USER') ?: 'root';
            $passwd = getenv('DB_PASSWORD') ?: 'example';

            return new PDO(
                "mysql:host={$host};port={$port};dbname={$name}",
                $user,
                $passwd,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND =>
                        "SET NAMES 'utf8', ".
                        "sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                ]
            );
        },
        Client::class => function (ContainerInterface $container) {
            $esHost = getenv('ES_HOST') ?: 'search';
            $esProto = getenv('ES_PROTO') ?: 'http';
            $esPort = getenv('ES_PORT') ?: 9200;
            $esUser = getenv('ES_USER') ?: 'elastic';
            $esPass = getenv('ES_PASSWORD') ?: 'changeme';

            $hosts = [
                "{$esProto}://{$esUser}:{$esPass}@{$esHost}:{$esPort}",
            ];
            $client = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();

            return $client;
        },

        RouterInterface::class => function () {
            return new RouteCollection();
        },

        TemplateRendererInterface::class => function (ContainerInterface $container) {
            $host = getenv('GA_HOST') ?: 'http://klingogbang.is';
            $tracking = getenv('GA_TRACH') ?: 'UA-146902881-1';

            $fbId = getenv('FB_ID') ?: '2085720918322296';
            $fbSecret = getenv('FB_SECRET') ?: '813a22630cace0901074dd8ad5188cb8';
            $fbRedirect = getenv('FB_REDIRECT') ?: 'http://localhost/login';

            return (new TwigRenderer(
                    './templates/',
                    getenv('ENVIRONMENT') === 'development',
                    getenv('ENVIRONMENT') !== 'development' ? './data/cache' : null
                ))
                ->addPath('./templates/app', 'app')
                ->addPath('./templates/dashboard', 'dashboard')
                ->addPath('./templates/partials', 'partials')
                ->addPath('./templates/error', 'error')
                ->addPath('./templates/layout', 'layout')
                ->addDefaultParam('app', 'host', $host)
                ->addDefaultParam('app', 'ga_tracking', $tracking)
                ->addDefaultParam('app', 'fb_id', $fbId)
                ->addDefaultParam('app', 'fb_secret', $fbSecret)
                ->addDefaultParam('app', 'fb_redirect', $fbRedirect)
                ->addExtension(new MarkdownExtension(new ParesDownAdapter()))
                ->addExtension(new Filters\Slug())
                ->addExtension(new Filters\Translate())
                ->addExtension(new Filters\Date())
                ->addExtension(new Filters\Path($container->get(RouterInterface::class)));
        }
    ],
];
