# Smart-Dressing
Web Architecture project
## Start server
````
composer update
composer install
symfony server:start
````

## Create entity
````
php bin/console make:entity
````

## Migrations
````
php bin/console make:migration
php bin/console doctrine:migrations:migrate
````