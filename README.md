# Snowtricks

[![SymfonyInsight](https://insight.symfony.com/projects/7d2ba0ba-9610-4212-8ccd-7f2d26096969/big.svg)](https://insight.symfony.com/projects/7d2ba0ba-9610-4212-8ccd-7f2d26096969)

This is the project 6, an exercise from [openclassrooms.com](https://openclassrooms.com/) in order to learn how to build a website with Symfony.
The theme is to build a community blog called "Snowtricks". 
Any user can make his own trick explanations using some pictures and videos.

For this project I'm using Symfony 4.

### Requirements

* Php 7.1 or greater
* Composer
* Mysql database

### Steps to install this project

1. Clone the repository
```
git clone https://github.com/keymener/snowtricks.git
```
2. Install dependecies with composer
```
cd snowtricks
composer install
```
3. Configure your .env in order to link your project with your mysql database and mail system.
```
APP_DATABASE:
```
4. Create the database
```
php bin/console doctrine:database:create
```
5. Update database shema
```
php bin/console doctrine:migrations:migrate
```
6. Load data fixtures to the database
```
php bin/console doctrine:fixtures:load
```
7. Run your server
```
php bin/console server:run
```

### Finaly...

Now you can test the application in dev environment with the generic user: userTest, password: test
You can also run few functionnal and unit tests with phpunit:
```
bin/phpunit
```

you can also try this project [Here](http://vps673214.ovh.net "Snowtricks")
