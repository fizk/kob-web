ssh -oKexAlgorithms=+diffie-hellman-group1-sha1 123.123.123.123

```
scp -r /Users/einar.adalsteinsson/workspace/ex/auto/* althingi:/home/kogb/auto
scp -r /Users/einar.adalsteinsson/workspace/ex/src/* althingi:/home/kogb/src
scp -r /Users/einar.adalsteinsson/workspace/ex/config/routes.php althingi:/home/kogb/config
scp -r /Users/einar.adalsteinsson/workspace/ex/public/scripts/* althingi:/home/kogb/public/scripts
scp -r /Users/einar.adalsteinsson/workspace/ex/public/styles/* althingi:/home/kogb/public/styles
scp -r /Users/einar.adalsteinsson/workspace/ex/Dockerfile althingi:/home/kogb/
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

* 9201
* 9301

To index everything in the database against the search engine, run:

```
$ docker-compose exec web bash -c "php /var/www/bin/search.php"
```

### Database
