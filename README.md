Answer API Demo
===============

Question/Answer with RESTful API demo application

Installation
------------

```
git clone git@github.com:bigfoot90/demo-answer.git
cd demo-answer
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console hautelook_alice:doctrine:fixtures:load
php app/console server:run
```


Tests
-----

Launch PhpUnit inside the project's directory
```
phpunit
```
