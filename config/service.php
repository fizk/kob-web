<?php

use Psr\Container\ContainerInterface;
use App\Auth\ParesDownAdapter;
use App\Auth\SimpleAuthAdapter;
use App\Service;
use App\Handler;
use App\Middleware;
use App\Filters;
use App\Router\RouterInterface;
use App\Router\RouteCollection;
use App\Template\TwigRenderer;
use App\Template\TemplateRendererInterface;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Authentication\Adapter\AdapterInterface;
use Aptoma\Twig\Extension\MarkdownExtension;

return [
    'factories'  => [
        Handler\Image\AssetPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Image\AssetPageHandler(
                $container->get(Service\Asset::class)
            );
        },
        Handler\Image\ImageSavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Image\ImageSavePageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\Image::class),
                $container->get(Service\Asset::class),
            );
        },
        Handler\Image\ImageUpdatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Image\ImageUpdatePageHandler(
                $container->get(Service\Image::class)
            );
        },
        Handler\HomePageHandler::class => function (ContainerInterface $container) {
            return new Handler\HomePageHandler(
                $container->get(Service\Entry::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\EntryPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntryPageHandler(
                $container->get(Service\Entry::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\EntryUpdatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntryUpdatePageHandler(
                $container->get(Service\Entry::class),
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
                $container->get(Service\Entry::class),
                $container->get(Service\Search::class)
            );
        },
        Handler\Entry\EntryDeletePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntryDeletePageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\Entry::class)
            );
        },
        Handler\Entry\EntriesPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\EntriesPageHandler(
                $container->get(Service\Entry::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Entry\ProjectsPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Entry\ProjectsPageHandler(
                $container->get(Service\Entry::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Author\AuthorsPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorsPageHandler(
                $container->get(Service\Author::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Author\AuthorPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorPageHandler(
                $container->get(Service\Author::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Author\AuthorsSearchPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorsSearchPageHandler(
                $container->get(Service\Author::class)
            );
        },
        Handler\Page\ManifestoPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\ManifestoPageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\Manifesto::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Page\StorePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\StorePageHandler(
                $container->get(Service\Manifesto::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Page\SupportersPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\SupportersPageHandler(
                $container->get(Service\Manifesto::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\DashboardPageHandler::class => function (ContainerInterface $container) {
            return new Handler\DashboardPageHandler(
                $container->get(Service\Entry::class),
                $container->get(Service\Author::class),
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
                $container->get(AuthenticationServiceInterface::class),
                $container->get(AdapterInterface::class)
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
                $container->get(Service\Author::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Author\AuthorSavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Author\AuthorSavePageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\Author::class)
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
                $container->get(Service\Author::class)
            );
        },
        Handler\Page\ManifestoSavePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\ManifestoSavePageHandler(
                $container->get(RouterInterface::class),
                $container->get(Service\Manifesto::class)
            );
        },
        Handler\Page\ManifestoUpdatePageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\ManifestoUpdatePageHandler(
                $container->get(Service\Manifesto::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\SearchPageHandler::class => function (ContainerInterface $container) {
            return new Handler\SearchPageHandler(
                $container->get(Service\Search::class),
                $container->get(TemplateRendererInterface::class)
            );
        },
        Handler\Page\RssPageHandler::class => function (ContainerInterface $container) {
            return new Handler\Page\RssPageHandler(
                $container->get(Service\Entry::class),
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
                $container->get(Service\Entry::class)
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
                $container->get(Service\Manifesto::class)
            );
        },

        AdapterInterface::class => function (ContainerInterface $container) {
            return new SimpleAuthAdapter(
                $container->get(Service\User::class)
            );
        },
        AuthenticationServiceInterface::class => function (ContainerInterface $container) {
            return new AuthenticationService(
                null,
                $container->get(AdapterInterface::class)
            );
        },
        \Mezzio\Helper\BodyParams\BodyParamsMiddleware::class => function () {
            return new \Mezzio\Helper\BodyParams\BodyParamsMiddleware();
        },

        Service\Entry::class => function (ContainerInterface $container) {
            return new Service\Entry($container->get(PDO::class));
        },
        Service\User::class => function (ContainerInterface $container) {
            return new Service\User($container->get(PDO::class));
        },
        Service\Author::class => function (ContainerInterface $container) {
            return new Service\Author($container->get(PDO::class));
        },
        Service\Manifesto::class => function (ContainerInterface $container) {
            return new Service\Manifesto($container->get(PDO::class));
        },
        Service\Image::class => function (ContainerInterface $container) {
            return new Service\Image($container->get(PDO::class));
        },
        Service\Search::class => function (ContainerInterface $container) {
            return new Service\Search($container->get(Client::class));
        },
        Service\Asset::class => function (ContainerInterface $container) {
            return new Service\Asset('./image-cache/');
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
        Client::class =>     function (ContainerInterface $container) {
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

            return (new TwigRenderer('./templates/'))
                ->addPath('./templates/app', 'app')
                ->addPath('./templates/partials', 'partials')
                ->addPath('./templates/error', 'error')
                ->addPath('./templates/layout', 'layout')
                ->addDefaultParam('app', 'host', $host)
                ->addDefaultParam('app', 'ga_tracking', $tracking)
                ->addExtension(new MarkdownExtension(new ParesDownAdapter()))
                ->addExtension(new Filters\Slug())
                ->addExtension(new Filters\Date())
                ->addExtension(new Filters\Year())
                ->addExtension(new Filters\RFC822())
                ->addExtension(new Filters\Path($container->get(RouterInterface::class)));
        }
        // Filters\ClassModifiers::class => Factory\ClassModifiersFactory::class
    ],
];
