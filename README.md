# Snowtricks
This is an exercise from openclassrooms.com in order to learn how to build a website with Symfony

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
3. Configure your .env in order to link your project with your mysql database

4. Create the database
```
php bin/console doctrine:database:create
```
5. Execute a migration with the latest version
```
php bin/console doctrine:migrations:migrate
```
6. Load data fixtures to the database
```
php bin/console doctrine:fixtures:load
```
