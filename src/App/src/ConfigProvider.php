<?php

declare(strict_types=1);

namespace App;

use PDO;
use App\Auth\SimpleAuthAdapter;
use App\Service;
use App\Factory;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Elasticsearch\Client as ElasticSearchClient;
use Elasticsearch\ClientBuilder as ElasticSearchClientBuilder;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'twig'         => $this->getTwig(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [

            ],
            'factories'  => [
                Handler\AssetPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\AssetPageHandler();
                },


                Handler\ImageSavePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\ImageSavePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Image::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\ImageUpdatePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\ImageUpdatePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Image::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\HomePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\HomePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\EntryPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\EntryPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\EntryUpdatePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\EntryUpdatePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\EntryCreatePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\EntryCreatePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\EntrySavePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\EntrySavePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(Service\Author::class),
                        $container->get(Service\Image::class),
                        $container->get(Service\Search::class)
                    );
                },
                Handler\EntryDeletePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\EntryDeletePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class)
                    );
                },
                Handler\EntriesPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\EntriesPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\ProjectsPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\ProjectsPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\AuthorsPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\AuthorsPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Author::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\AuthorsSearchPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\AuthorsSearchPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Author::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\ManifestoPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\ManifestoPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Manifesto::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\StorePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\StorePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Manifesto::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\DashboardPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\DashboardPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(Service\Author::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\LoginPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\LoginPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Entry::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\LoginSubmitPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\LoginSubmitPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(AuthenticationService::class),
                        $container->get(SimpleAuthAdapter::class)
                    );
                },
                Handler\LogoutSubmitPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\LogoutSubmitPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(AuthenticationService::class),
                        $container->get(SimpleAuthAdapter::class)
                    );
                },
                Handler\AuthorUpdatePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\AuthorUpdatePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Author::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\AuthorSavePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\AuthorSavePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Author::class)
                    );
                },
                Handler\AuthorCreatePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\AuthorCreatePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Author::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\AuthorDeletePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\AuthorDeletePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Author::class)
                    );
                },
                Handler\ManifestoSavePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\ManifestoSavePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Manifesto::class)
                    );
                },
                Handler\ManifestoUpdatePageHandler::class => function (ContainerInterface $container) {
                    return new Handler\ManifestoUpdatePageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Manifesto::class),
                        $container->get(TemplateRendererInterface::class)
                    );
                },
                Handler\SearchPageHandler::class => function (ContainerInterface $container) {
                    return new Handler\SearchPageHandler(
                        $container->get(RouterInterface::class),
                        $container->get(Service\Search::class),
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
                        $container->get(AuthenticationService::class)
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
                        $container->get(AuthenticationService::class)
                    );
                },
                Middleware\AdminMenuMiddleware::class => function (ContainerInterface $container) {
                    return new Middleware\AdminMenuMiddleware(
                        $container->get(TemplateRendererInterface::class),
                        $container->get(Service\Manifesto::class)
                    );
                },

                SimpleAuthAdapter::class => function (ContainerInterface $container) {
                    return new SimpleAuthAdapter(
                        $container->get(Service\User::class)
                    );
                },
                AuthenticationService::class => function (ContainerInterface $container) {
                    return new AuthenticationService(
                        null,
                        $container->get(SimpleAuthAdapter::class)
                    );
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
                    return new Service\Search($container->get(ElasticSearchClient::class));
                },

                PDO::class => Factory\DataSourceFactory::class,

                ElasticSearchClient::class => function (ContainerInterface $container) {
                        $esHost = getenv('ES_HOST') ?: 'search';
                        $esProto = getenv('ES_PROTO') ?: 'http';
                        $esPort = getenv('ES_PORT') ?: 9200;
                        $esUser = getenv('ES_USER') ?: 'elastic';
                        $esPass = getenv('ES_PASSWORD') ?: 'changeme';

                        $hosts = [
                            "{$esProto}://{$esUser}:{$esPass}@{$esHost}:{$esPort}",
                        ];
                        $client = ElasticSearchClientBuilder::create()
//                                ->setLogger($sm->get(LoggerInterface::class))
                            ->setHosts($hosts)
                            ->build();

                        return $client;
                },
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'app'      => [__DIR__ . '/../templates/app'],
                'partials' => [__DIR__ . '/../templates/partials'],
                'error'    => [__DIR__ . '/../templates/error'],
                'layout'   => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }

    public function getTwig(): array
    {
        return [
            'globals' => [
                'host' => 'http://this.is/klingogbang',
                'ga_tracking' => 'UA-XXXXX-X',
            ],
            'extensions' => [
                \Aptoma\Twig\Extension\MarkdownExtension::class => new \Aptoma\Twig\Extension\MarkdownExtension(
                    new class implements \Aptoma\Twig\Extension\MarkdownEngineInterface {
                        public function transform($content) {
                            return \Parsedown::instance()
                                ->setSafeMode(false)
                                ->setMarkupEscaped(false)
                                ->text($content);
                        }
                        public function getName() {
                            return 'erusev/parsedown';
                        }
                    }
                ),
                Filters\Slug::class => new Filters\Slug(),
                Filters\Date::class => new Filters\Date(),
            ]
        ];
    }
}
