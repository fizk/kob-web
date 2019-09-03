<?php

declare(strict_types=1);

namespace App;

use PDO;
use App\Auth\SimpleAuthAdapter;
use App\Service;
use App\Factory;
use Zend\Authentication\AuthenticationService;
use Elasticsearch\Client;
use Aptoma\Twig\Extension\MarkdownExtension;


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
                Handler\AssetPageHandler::class => Factory\AssetPageHandlerFactory::class,
                Handler\ImageSavePageHandler::class => Factory\ImageSavePageHandlerFactory::class,
                Handler\ImageUpdatePageHandler::class => Factory\ImageUpdatePageHandlerFactory::class,
                Handler\HomePageHandler::class => Factory\HomePageHandlerFactory::class,
                Handler\EntryPageHandler::class => Factory\EntryPageHandlerFactory::class,
                Handler\EntryUpdatePageHandler::class => Factory\EntryUpdatePageHandlerFactory::class,
                Handler\EntryCreatePageHandler::class => Factory\EntryCreatePageHandlerFactory::class,
                Handler\EntrySavePageHandler::class => Factory\EntrySavePageHandlerFactory::class,
                Handler\EntryDeletePageHandler::class => Factory\EntryDeletePageHandlerFactory::class,
                Handler\EntriesPageHandler::class => Factory\EntriesPageHandlerFactory::class,
                Handler\ProjectsPageHandler::class => Factory\ProjectsPageHandlerFactory::class,
                Handler\AuthorsPageHandler::class => Factory\AuthorsPageHandlerFactory::class,
                Handler\AuthorPageHandler::class => Factory\AuthorPageHandlerFactory::class,
                Handler\AuthorsSearchPageHandler::class => Factory\AuthorsSearchPageHandlerFactory::class,
                Handler\ManifestoPageHandler::class => Factory\ManifestoPageHandlerFactory::class,
                Handler\StorePageHandler::class => Factory\StorePageHandlerFactory::class,
                Handler\SupportersPageHandler::class => Factory\SupportersPageHandlerFactory::class,
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
                Handler\SearchPageHandler::class => Factory\SearchPageHandlerFactory::class,
                Handler\RssPageHandler::class => Factory\RssPageHandlerFactory::class,

                Middleware\SecondaryLanguageMiddleware::class => Factory\SecondaryLanguageMiddlewareFactory::class,
                Middleware\PrimaryLanguageMiddleware::class => Factory\PrimaryLanguageMiddlewareFactory::class,
                Middleware\SessionMiddleware::class => Factory\SessionMiddlewareFactory::class,
                Middleware\MenuMiddleware::class => Factory\MenuMiddlewareFactory::class,
                Middleware\AuthenticationMiddleware::class => Factory\AuthenticationMiddlewareFactory::class,
                Middleware\AdminMenuMiddleware::class => Factory\AdminMenuMiddlewareFactory::class,

                SimpleAuthAdapter::class => Factory\SimpleAuthAdapterFactory::class,
                AuthenticationService::class => Factory\AuthenticationServiceFactory::class,

                Service\Entry::class => Factory\EntryFactory::class,
                Service\User::class => Factory\UserFactory::class,
                Service\Author::class => Factory\AuthorFactory::class,
                Service\Manifesto::class => Factory\ManifestoFactory::class,
                Service\Image::class => Factory\ImageFactory::class,
                Service\Search::class => Factory\SearchFactory::class,

                PDO::class => Factory\DataSourceFactory::class,
                Client::class => Factory\ClientFactory::class,

                MarkdownExtension::class => Factory\ParesDownAdapterFactory::class,
                Filters\Slug::class => Factory\SlugFactory::class,
                Filters\Date::class => Factory\DateFactory::class,
                Filters\Year::class => Factory\YearFactory::class,
                Filters\RFC822::class => Factory\RFC822Factory::class,
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
                'host' => 'http://klingogbang.is',
                'ga_tracking' => 'UA-146902881-1',
            ],
            'extensions' => [
                MarkdownExtension::class,
                Filters\Slug::class,
                Filters\Date::class,
                Filters\Year::class,
                Filters\RFC822::class,
            ],
        ];
    }
}
