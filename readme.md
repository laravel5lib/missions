[![Build Status](https://travis-ci.org/missions-me/missions.svg?branch=master)](https://travis-ci.org/missions-me/missions)

# Missions.Me

All-In-One web application for managing missions trips and humanitarian projects.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

#### Local Machine Requirements
All of these requirements are satisfied by the Laravel Homestead virtual machine, so it's highly recommended that you use [Homestead](https://laravel.com/docs/5.4/homestead) as your local development environment.

However, if you are not using Homestead, you will need to make sure your local machine meets the following requirements:

 - PHP >= 5.6.4
 - OpenSSL PHP Extension
 - PDO PHP Extension
 - Mbstring PHP Extension
 - Tokenizer PHP Extension
 - XML PHP Extension

If your local machine meets the requirements above then we highly recommend using [Valet](https://laravel.com/docs/5.4/valet), which is a Laravel development environment for Mac minimalists. It is much faster and simpler to use than Homestead!

#### Latest Version of Composer

This project utilizes [Composer](https://getcomposer.org/) to manage its dependencies. So, before using this project, make sure you have Composer installed on your machine.

If you don't have composer, run this in your terminal to install the latest Composer version:
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

If you already have Composer installed, then run this in your terminal to upgrade to the latest version:
```
composer self-update
```

#### Latest Version of Node.Js and NPM
You must ensure that Node.js and NPM are installed on your machine.

To check if node and npm are installed on your local machine, run this in your terminal:
```
node -v
npm -v
```

By default, Laravel Homestead includes everything you need; however, if you aren't using Vagrant, then you can easily install the latest version of Node and NPM using simple graphical installers from their [download page](https://nodejs.org/en/download/).

#### Compile Assets with Gulp
This project uses gulp to compile it's JavaScript and CSS.

If you do not have the gulp installed, run this in your terminal:
```
npm install --global gulp-cli
```

### Installing

The following is a step by step process to get a development env running.

#### Install Dependencies

Install PHP dependencies by running this in your terminal:
```
composer install
```

Install the JavaScript and CSS dependencies by running this in your terminal:
```
npm install
```

#### Migrate the Database

Builds the database and populates it with fake data:
```
php artisan migrate:refresh --seed
```

#### Build the Application

Run this in your terminal to compile all the JavaScript and CSS assets:
```
gulp
```

#### Log with a test user

Navigate to the `/login` page and use these test credentials:
```
admin@admin.com
secret
```

## Running the tests

From the projects root or tests directory run this in your terminal:
```
phpunit
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

 - [Laravel 5.4](https://laravel.com/docs/5.4)
 - [Vue.js 2](https://vuejs.org/v2/guide/)
 - [Bootstrap 3](https://getbootstrap.com/docs/3.3/)

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/missions-me/missions/tags).

## Authors

* **Neil Keena** - *Initial work* - [nkeena](https://github.com/nkeea)

See also the list of [contributors](https://github.com/missions-me/missions/contributors) who participated in this project.

## Acknowledgments

* Spatie for their numerous packages used in the project
