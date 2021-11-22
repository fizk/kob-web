# Kling&amp;Bang Website.

Kling&Bang Web is a simple CMS system for the Kling&Bang Gallery.

It is a PHP.8 web application using the classic MVC programing paradigm. Is uses MySQL as the data-store and Elasticsearch to perform free-text search.

## Architecture / overview.
This application tries to use the [PSR](https://www.php-fig.org/psr/) interfaces when it can. All requests are (or should be) routed to `public/index.php`, that is the entry-point for the application.

Incoming requests are wrapped in a [PSR 7 ServerRequestInterface](https://www.php-fig.org/psr/psr-7/). An instance of a [PSR-11: Container interface](https://www.php-fig.org/psr/psr-11/) is created and a Router is extracted from it. (The Router is configured in `config.router.php`) The request is matched against the Router and if a match is found, an associated [PSR-15: HTTP Server Request Handlers](https://www.php-fig.org/psr/psr-15/) is executed.

Handlers are also extracted from a [PSR-11: Container interface](https://www.php-fig.org/psr/psr-11/). (The Container is configured in `config/service.php`). Before they are executed, they are initialized in the Container and [injected](https://en.wikipedia.org/wiki/Dependency_injection) with the services they need. (Mostly the Template Engine and the Database Services).

The Container interface, or the ServiceManager initializes all services. This includes the TemplateEngine, the Router, the Database Services, the Database Gateway, the Search Gateway and the various [Middlewares](https://www.php-fig.org/psr/psr-15/) requires to service the Request.

The `App\Template\TwigRenderer` is an [Adapter](https://en.wikipedia.org/wiki/Adapter_pattern) for the [Twig](https://twig.symfony.com/) Template Engine. It abstracts away the Twig details so if there would ever be a need to switch template engine, that would be possible.

Database services (`src/Service`) are injected with a PDO objects in the Container. Their job is to interact with the Database (which is MySQL) without letting layers above (mostly the Handlers) know the database tables. Database services are used the Handlers to read or modify data in the database.

Authentication is performed by [laminas-authentication](https://github.com/laminas/laminas-authentication). This application comes with two Authentication Adapters. **PasswordAuthAdapter** for a username/password authentication and a **FacebookAuthAdapter** to login via a Facebook account.

This application uses a few Middleware functions. Middleware intercepts an incoming Requests and augments them as required.

* **AuthenticationMiddleware**, Does authentication.
* **SessionMiddleware**, Restricts access to a Handler if user is not logged in.
* **DetectLanguageMiddleware**, Tries to figure out which language should be used (IS/EN)
* **PrimaryLanguageMiddleware** Forces primary language (IS)
* **SecondaryLanguageMiddleware** Forces secondary language (EN)







### Development.
Clone this repo.
```sh
$ docker-compose up -d web
```
If you need your IDE to recognize 3rd party libraries, you might need to install PHP dependencies by using [composer](https://getcomposer.org/). In which case you have to run `composer install`.

### Database
The data/entires are persisted in MySQL relational-database. The schema is kept in a a [git-repo](https://github.com/fizk/kob-db) but also as a Docker image: `docker push einarvalur/kob-db:tagname`.

The database in the schema is named `klingogbang`, be sure to provide that value to the **web** system so it can access the database.

### Search
This system uses Elasticsearch. You can seed the search-engine, by running `docker-compose exec web bash -c "php /var/www/bin/search.php"`. It will request all Entries from the database and import them into the Elasticsearch cluster.


### Images
When an image is uploaded, it is cropped so that is doesn't exceed 2560x1600 px. Then it is placed in the `image-cache` directory. To persist images between containers, mount this directory into the host system. A simple way to do this would be to just keep the cache directory in the same place as the docker-compose file and then mount it like so `./image-cache:/var/www/image-cache`.

When a cropped version of an image is requested, first the system will go into `image-cache` and find the "original" version and then crop it to size. The cropped version is kept in the public's `img` directory. Again, to persist the cropped images between containers, mount the `img` folder to the host system. To mount in the project's root directory, you can do this `./image-crop:/var/www/html/img`.

Both these directories need to have read/write for any user.


`XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html ./test/doc`
