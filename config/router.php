<?php declare(strict_types=1);

use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use App\Handler;
use App\Middleware;

return [
    '/' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\HomePageHandler::class
            ],
            'heim'
        ],
    ],

    '/home' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\HomePageHandler::class
            ],
            'home'
        ],
    ],

    '/listi/{year}' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'listi'
        ],
    ],

    '/list/{year}' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'list'
        ],
    ],

    '/verkefni' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Entry\ProjectsPageHandler::class
            ],
            'verkefni'
        ],
    ],

    '/projects' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Entry\ProjectsPageHandler::class
            ],
            'projects'
        ],
    ],

    '/syningar' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'syningar'
        ],
    ],

    '/shows' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'entries'
        ],
    ],

    '/syningar/{id}' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Entry\EntryPageHandler::class
            ],
            'syning'
        ],
    ],

    '/shows/{id}' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Entry\EntryPageHandler::class
            ],
            'entry'
        ],
    ],

    '/listamenn' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Author\AuthorsPageHandler::class
            ],
            'listamenn'
        ],
    ],

    '/authors' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Author\AuthorsPageHandler::class
            ],
            'authors'
        ],
    ],

    '/listamenn/{id}' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Author\AuthorPageHandler::class
            ],
            'listamadur'
        ],
    ],

    '/authors/{id}' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Author\AuthorPageHandler::class
            ],
            'author'
        ],
    ],

    '/um' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Page\ManifestoPageHandler::class
            ],
            'um'
        ],
    ],

    '/about' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Page\ManifestoPageHandler::class
            ],
            'about'
        ],
    ],

    '/verslun' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Page\StorePageHandler::class
            ],
            'verslun'
        ],
    ],

    '/store' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Page\StorePageHandler::class
            ],
            'store'
        ],
    ],

    '/vinir' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\Page\SupportersPageHandler::class
            ],
            'velunnarar'
        ],
    ],

    '/friends' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\Page\SupportersPageHandler::class
            ],
            'supporters'
        ],
    ],

    '/leit' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Handler\SearchPageHandler::class
            ],
            'leit'
        ],
    ],

    '/search' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Handler\SearchPageHandler::class
            ],
            'search'
        ],
    ],

    '/rss' => [
        'GET' => [
            Handler\Page\RssPageHandler::class,
            'rss'
        ],
    ],

    '/login' => [
        'GET' => [
            Handler\Login\LoginPageHandler::class,
            'login',
        ],
        'POST' => [
            Handler\Login\LoginSubmitPageHandler::class,
            'login-submit'
        ],
    ],

    '/logout' => [
        'GET' => [
            Handler\Login\LogoutSubmitPageHandler::class,
            'logout-submit'
        ],
    ],

    '/img/{size}/{name}' =>  [
        'GET' => [
            Handler\Image\AssetPageHandler::class,
            'asset'
        ],
    ],

    '/image' => [
        'POST' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Handler\Image\ImageSavePageHandler::class
            ],
            'images-save'
        ],
    ],

    '/api/author/search' => [
        'GET' => [
            Handler\Author\AuthorsSearchPageHandler::class,
            'author-search'
        ],
    ],

    '/update' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\DashboardPageHandler::class
            ],
            'update'
        ],
    ],

    '/update/entry' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Entry\EntryCreatePageHandler::class
            ],
            'create-entry'
        ],
        'POST' => [
            [
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Entry\EntrySavePageHandler::class
            ],
            'new-entry'
        ],
    ],

    '/update/entry/{id}' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Entry\EntryUpdatePageHandler::class
            ],
            'update-entry'
        ],
        'POST' => [
            [
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Entry\EntrySavePageHandler::class
            ],
            'save-entry'
        ],
    ],

    '/delete/entry/{id}' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Entry\EntryDeletePageHandler::class
            ],
            'delete-entry'
        ],
    ],

    '/update/author/{id}' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Author\AuthorUpdatePageHandler::class
            ],
            'update-author'
        ],
        'POST' => [
            [
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Author\AuthorSavePageHandler::class
            ],
            'save-author'
        ],
    ],

    '/update/author' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Author\AuthorCreatePageHandler::class
            ],
            'create-author'
        ],
        'POST' => [
            [
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Author\AuthorSavePageHandler::class
            ],
            'new-author'
        ],
    ],

    '/delete/author/{id}' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Author\AuthorDeletePageHandler::class
            ],
            'delete-author'
        ],
    ],

    '/update/manifesto/{id}' => [
        'GET' => [
            [
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Page\ManifestoUpdatePageHandler::class
            ],
            'update-manifesto'
        ],
        'POST' => [
            [
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Page\ManifestoSavePageHandler::class
            ],
            'new-manifesto'
        ],
    ],

    '/update/image/{id}' => [
        'POST' => [
            [
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Image\ImageUpdatePageHandler::class
            ],
            'update-image'
        ],
    ],

];
