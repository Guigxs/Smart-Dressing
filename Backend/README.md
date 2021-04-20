# Smart-Dressing
Web Architecture project
## Start server
````
composer update
composer install
symfony server:start
````

## Clear cache
For updating the entities groups you need to clear caches.

```
php bin/console cache:clear
```

## Create entity
````
php bin/console make:entity
````

## Migrations
````
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
````