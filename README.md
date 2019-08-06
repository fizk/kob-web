```
scp -r /Users/einar.adalsteinsson/workspace/ex/data/images/* althingi:/home/kogb/klingogbang/data/
```



ssh -oKexAlgorithms=+diffie-hellman-group1-sha1 root@kob.this.is
kaSSrRe12365.XXx45


scp -r -oKexAlgorithms=+diffie-hellman-group1-sha1 root@kob.this.is:/var/www/klingogbang/img/main

## Pre-install
Make sure your machine can handle ElasticSearch
https://www.elastic.co/guide/en/elasticsearch/reference/current/docker.html#docker-cli-run-prod-mode

## Install
Clone the repository. Run Docker Composer.  
```
$ docker-compose up -d
```

## Post-install
To create a mapping for ElasticSearch, connect to the cluster with Kibana and then run the mapping
found in ./auto/search/template.json

ElasticSearch is on ports

* `9201`
* `9301`

To index everything in the database against the search engine, run:

```
$ docker-compose exec web bash -c "php /var/www/bin/search.php"
```

### Database
The database has port `5506` exposed, so you can access the database via you favoured database client
