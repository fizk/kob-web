<?php declare(strict_types=1);

use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use App\Handler;
use App\Middleware;

return [
    '/' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\HomePageHandler::class
            ],
            'heim'
        ],
    ],

    '/home' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\HomePageHandler::class
            ],
            'home'
        ],
    ],

    '/listi/{year}' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'listi'
        ],
    ],

    '/list/{year}' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'list'
        ],
    ],

    '/verkefni' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\ProjectsPageHandler::class
            ],
            'verkefni'
        ],
    ],

    '/projects' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\ProjectsPageHandler::class
            ],
            'projects'
        ],
    ],

    '/syningar' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'syningar'
        ],
    ],

    '/shows' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\EntriesPageHandler::class
            ],
            'entries'
        ],
    ],

    '/syningar/{id}' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\EntryPageHandler::class
            ],
            'syning'
        ],
    ],

    '/shows/{id}' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Entry\EntryPageHandler::class
            ],
            'entry'
        ],
    ],

    '/listamenn' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Author\AuthorsPageHandler::class
            ],
            'listamenn'
        ],
    ],

    '/authors' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Author\AuthorsPageHandler::class
            ],
            'authors'
        ],
    ],

    '/listamenn/{id}' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Author\AuthorPageHandler::class
            ],
            'listamadur'
        ],
    ],

    '/authors/{id}' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Author\AuthorPageHandler::class
            ],
            'author'
        ],
    ],

    '/um' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Page\ManifestoPageHandler::class
            ],
            'um'
        ],
    ],

    '/about' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Page\ManifestoPageHandler::class
            ],
            'about'
        ],
    ],

    '/verslun' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Page\StorePageHandler::class
            ],
            'verslun'
        ],
    ],

    '/store' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Page\StorePageHandler::class
            ],
            'store'
        ],
    ],

    '/vinir' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Page\SupportersPageHandler::class
            ],
            'velunnarar'
        ],
    ],

    '/friends' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\Page\SupportersPageHandler::class
            ],
            'supporters'
        ],
    ],

    '/leit' => [
        'GET' => [
            [
                Middleware\PrimaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
                Handler\SearchPageHandler::class
            ],
            'leit'
        ],
    ],

    '/search' => [
        'GET' => [
            [
                Middleware\SecondaryLanguageMiddleware::class,
                Middleware\SessionMiddleware::class,
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

    '/fb-login' => [
        'GET' => [
            Handler\Login\FbLoginSubmitPageHandler::class,
            'fb-login-submit'
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

    '/api/search' => [
        'GET' => [
            Handler\ApiSearchPageHandler::class,
            'api-search'
        ],
    ],

    //
    //
    //
    //

    '/update' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
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
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Entry\EntryCreatePageHandler::class
            ],
            'create-entry'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
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
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Entry\EntryUpdatePageHandler::class
            ],
            'update-entry'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
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
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Entry\EntryDeletePageHandler::class
            ],
            'delete-entry'
        ],
    ],

    '/update/store' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Store\StoreCreatePageHandler::class
            ],
            'create-store'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Store\StoreSavePageHandler::class
            ],
            'new-store'
        ],
    ],

    '/update/store/{id}' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Store\StoreUpdatePageHandler::class
            ],
            'update-store'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Store\StoreSavePageHandler::class
            ],
            'save-store'
        ],
    ],

    '/delete/store/{id}' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Store\StoreDeletePageHandler::class
            ],
            'delete-store'
        ],
    ],

    '/update/author/{id}' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Author\AuthorUpdatePageHandler::class
            ],
            'update-author'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
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
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Author\AuthorCreatePageHandler::class
            ],
            'create-author'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
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
                Middleware\DetectLanguageMiddleware::class,
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
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\Page\PageUpdatePageHandler::class
            ],
            'update-manifesto'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Page\PageSavePageHandler::class
            ],
            'new-manifesto'
        ],
    ],

    '/update/image/{id}' => [
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\Image\ImageUpdatePageHandler::class
            ],
            'update-image'
        ],
    ],

    '/update/user' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\User\UsersPageHandler::class
            ],
            'create-user'
        ],
        'POST' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                BodyParamsMiddleware::class,
                Handler\User\UsersCreatePageHandler::class
            ],
            'new-user'
        ],
    ],

    '/delete/user/{id}' => [
        'GET' => [
            [
                Middleware\DetectLanguageMiddleware::class,
                Middleware\AuthenticationMiddleware::class,
                Middleware\AdminMenuMiddleware::class,
                Handler\User\UsersDeletePageHandler::class
            ],
            'delete-user'
        ],
    ],
];
