OH test task

Pre-requisites
--------------
* Docker

Installation
------------
* Clone the repository
* Run `docker-compose up -d` in the root directory
* Open `http://localhost:8080` in your browser
* if you want to run tests, run `docker-compose exec app php vendor/bin/phpunit`