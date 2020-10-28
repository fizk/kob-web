# Kling&amp;Bang Website.

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
