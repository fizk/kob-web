<?php

declare(strict_types=1);

namespace App;

use App\Auth\SimpleAuthAdapter;
use PDO;
use App\Service;
use App\Factory;
use Aptoma\Twig\Extension\MarkdownEngine;
use Zend\Authentication\AuthenticationService;

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
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\ImagePageHandler::class => Factory\ImagePageHandlerFactory::class,
                Handler\ImageSavePageHandler::class => Factory\ImagesavePageHandlerFactory::class,
                Handler\HomePageHandler::class => Factory\HomePageHandlerFactory::class,
                Handler\EntryPageHandler::class => Factory\EntryPageHandlerFactory::class,
                Handler\EntryUpdatePageHandler::class => Factory\EntryUpdatePageHandlerFactory::class,
                Handler\EntryCreatePageHandler::class => Factory\EntryCreatePageHandlerFactory::class,
                Handler\EntrySavePageHandler::class => Factory\EntrySavePageHandlerFactory::class,
                Handler\EntryDeletePageHandler::class => Factory\EntryDeletePageHandlerFactory::class,
                Handler\EntriesPageHandler::class => Factory\EntriesPageHandlerFactory::class,
                Handler\AuthorsPageHandler::class => Factory\AuthorsPageHandlerFactory::class,
                Handler\AuthorsSearchPageHandler::class => Factory\AuthorsSearchPageHandlerFactory::class,
                Handler\ManifestoPageHandler::class => Factory\ManifestoPageHandlerFactory::class,
                Handler\DashboardPageHandler::class => Factory\DashboardPageHandlerFactory::class,
                Handler\LoginPageHandler::class => Factory\LoginPageHandlerFactory::class,
                Handler\LoginSubmitPageHandler::class => Factory\LoginSubmitPageHandlerFactory::class,
                Handler\LogoutSubmitPageHandler::class => Factory\LogoutSubmitPageHandlerFactory::class,
                Handler\AuthorUpdatePageHandler::class => Factory\AuthorUpdatePageHandlerFactory::class,
                Handler\AuthorSavePageHandler::class => Factory\AuthorSavePageHandlerFactory::class,
                Handler\AuthorCreatePageHandler::class => Factory\AuthorCreatePageHandlerFactory::class,
                Handler\AuthorDeletePageHandler::class => Factory\AuthorDeletePageHandlerFactory::class,
                Handler\ManifestoSavePageHandler::class => Factory\ManifestoSavePageHandlerFactory::class,
                Handler\ManifestoUpdatePageHandler::class => Factory\ManifestoUpdatePageHandlerFactory::class,

                Middleware\SessionMiddleware::class => Factory\SessionMiddlewareFactory::class,
                Middleware\MenuMiddleware::class => Factory\MenuMiddlewareFactory::class,
                Middleware\AuthenticationMiddleware::class => Factory\AuthenticationMiddlewareFactory::class,

                SimpleAuthAdapter::class => Factory\SimpleAuthAdapterFactory::class,
                AuthenticationService::class => Factory\AuthenticationServiceFactory::class,

                Service\Entry::class => Factory\EntryFactory::class,
                Service\User::class => Factory\UserFactory::class,
                Service\Author::class => Factory\AuthorFactory::class,
                Service\Manifesto::class => Factory\ManifestoFactory::class,
                Service\Image::class => Factory\ImageFactory::class,
                PDO::class => Factory\DataSourceFactory::class,
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
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
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
                    new MarkdownEngine\MichelfMarkdownEngine()
                ),
            ]
        ];
    }
}
