# Upgrading

This document talks about upgrading between different versions of Kickstart.

## From 1.4.2 to 2.0.0

Starting with version 2.0, Kickstart has been split into two packages - the system and user parts. That means that
some files were removed from the applications' directory, while some were changed.

Upgrade process from 1.4.2 to 2.0.0 is pretty straightforward, but there are a couple of files that need to be modified
by hand, i.e. it's not possible to just copy over their contents from the 2.0.0 branch.

### Dependencies

* Remove the reference to the `git@bitbucket.org:NoctisPL/database-lib.git` repository in `composer.json`.
* Run the following command to remove obsolete packages:
  ```shell
  composer remove ext-pdo nikic/fast-route noctis/database-lib php-di/php-di psr/http-server-middleware twig/twig vlucas/phpdotenv vimeo/psalm
  ```
* Run the following commands to install new packages & update existing:
  ```shell
  composer update symfony/service-contracts symfony/polyfill-mbstring
  composer require --ignore-platform-reqs php:^8.0 php-di/php-di:^6.3 symfony/console:^5.2 symfony/http-foundation:^5.2
  composer require --ignore-platform-reqs --dev squizlabs/php_codesniffer:^3.5 symfony/var-dumper:^5.2 vimeo/psalm:^4.4
  ```
* Run the following command to install the `noctis/kickstart` base package:
  ```shell
  composer require noctis/kickstart:^2.0
  ```

### Console Commands

* Edit the `bin/console` file. Replace the contents of the file with contents of the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/bin/console). Make sure to copy over any
  references to console commands classes (classes from the `App/Console/Command` namespace) from the original file,
  so that those commands can still be called from the CLI.
* Remove the `src/Console/Command/BaseCommand.php` file. 
* Edit any `*Command.php` files within `src/Console/Command` directory and make sure those classes extend the
  `Noctis\KickStart\Console\Command\AbstractCommand` abstract class.
* Copy the `Application.php` file from 
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Console/Application.php) to the
  `src/Console` directory.
  
### HTTP

* Delete the `src/Http/Action/BaseAction.php` file.
* Edit any `*Action.php` files within `src/Http/Action` directory and make sure those classes extend the
  `Noctis\KickStart\Http\Action\AbstractAction` class.
* Delete the `src/Http/Factory`, `src/Http/Helper` directories.
* Delete the `ActionInvoker.php`, `RequestHandlerInterface.php` and `RequestHandlerStack.php` files from the
  `src/Http/Middleware` directory.
* Delete the `src/Http/Middleware/Guard/GuardMiddlewareInterface.php` file.
* Edit any `*Guard.php` files in the `src/Http/Middleware/Guard` directory and:
  * make sure those classes extend (not implement!) the `Noctis\KickStart\Http\Middleware\AbstractMiddleware` abstract 
    class,
  * change the reference to `RequestHandlerInterface` interface from `App\Http\Middleware\RequestHandlerInterface` to 
    `Noctis\KickStart\Http\Middleware\RequestHandlerInterface`,
  * change the reference to the `HttpRedirectionTrait` trait from `App\Http\Helper\HttpRedirectionTrait` to 
    `Noctis\KickStart\Http\Helper\HttpRedirectionTrait`
* Delete the `src/Http/Request/AbstractRequest.php` file.
* Edit any `*Request.php` files in the `src/Http/Request` directory and make sure those classes extend the
  `Noctis\KickStart\Http\Request\AbstractRequest` abstract class.
* Create a directory named `Routing` inside the `src/Http` directory. Inside it, create a file called
  `routes.php`, with the following contents:
  ```php
  <?php

  declare(strict_types=1);

  return [
  ];
  ```
* Modify the `src/Http/Routing/routes.php` file by copying over routes defined in the 
  `src/Http/Routes/StandardRoutes.php` file. For example, a route such as this:
  ```php
  use App\Http\Action\DummyAction;
  use App\Http\Middleware\Guard\DummyGuard;
  
  $r->get('[{name}]', [
      DummyAction::class,
      [
          DummyGuard::class,
      ],
  ]);
  ```
  should be transformed into the following route definition:
  ```php
  use App\Http\Action\DummyAction;
  use App\Http\Middleware\Guard\DummyGuard;
  
  ['GET', '/[{name}]', DummyAction::class, [DummyGuard::class]],
  ```
* Once you're done transposing the routes from `StandardRoutes.php` to `routes.php`, delete the `src/Http/Routes` 
  directory, along with the `StandardRoutes.php` file inside.
* Delete the `src/Http/Router.php` file.
* Copy the `Application.php` file from
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Http/Application.php) to the `src/Http`
  directory.
* Edit the `public/index.php` file. Replace the contents of this file with the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/public/index.php).
* Edit the `public/.htaccess` file and add the following lines:
  ```apacheconf
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_URI} (.+)/$
  RewriteRule ^ %1 [L,R=301]
  ```
  between:
  ```apacheconf
  RewriteCond %{HTTPS} !=on
  RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L]
  ```
  and:
  ```apacheconf
  RewriteCond %{REQUEST_URI} !=/favicon.ico
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^ index.php [QSA,L]
  ```

### Service Providers

* Delete the `src/Provider/HttpServices.php`, `src/Provider/ServicesProviderInterface.php` and 
  `src/Provider/TwigServiceProvider.php` files.
* Edit any Service Provider files within `src/Provider` directory and make sure those classes implement the
  `Noctis\KickStart\Provider\ServicesProviderInterface` interface.
* Copy the [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Provider/ConfigurationProvider.php)
  of the `ConfigurationProvider.php` file into the `src/Provider` directory.
* If your application makes use of Repositories (in the `src/Repository` directory), copy the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Provider/RepositoryProvider.php) of the
  `RepositoryProvider.php` file into the `src/Provider` directory and edit it appropriately, moving over repository
  definitions from other Service Provider classes (e.g. `src/Provider/DummyServiceProvider.php`).
* Replace the contents of the `src/Provider/DatabaseConnectionProvider.php` file with contents of the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Provider/DatabaseConnectionProvider.php).
  If there were additional database connections defined there, you will need to transpose the appropriately, based on
  the primary database connection definition.
* If the `src/Provider/HttpMiddlewareProvider.php` file exists and contains definitions, they may need to be modified
  appropriately as the definition format changed in `2.0.0`. For example, the following definition, where a constructor
  parameter value is explicitly defined:
  ```php
  DummyGuard::class => [
      null, [
          'dummyParam' => getenv('dummy_param') === 'true',
      ]
  ],
  ```
  needs to be replaced with:
  ```php
  use function DI\autowire;
  
  DummyGuard::class => autowire(DummyGuard::class)
      ->constructorParameter(
          'dummyParam',
          true
      ),
  ```

### Repositories

* Delete the `src/Repository/DatabaseRepository.php` file.
* Copy the `AbstractDatabaseRepository.php` file from the 
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Repository/AbstractDatabaseRepository.php)
  to the `src/Repository` directory.
* Edit any `*Repository.php` files inside the `src/Repository` directory and make sure those classes extend the local
  `AbstractDatabaseRepository` abstract class.
  
### Other

* Copy the `src/Configuration` directory from the 
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/tree/master/src/Configuration) into the project's root
  directory.
* Copy the `src/Database` directory from the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/tree/master/src/Database) into the project's root directory.
* Delete the `src/Configuration.php` and `src/ContainerBuilder.php` files.
* Replace the contents of the `templates/layout.html.twig` file with the 
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/templates/layout.html.twig)
* Create the following directory path: `var/cache/templates` in the project's root directory.
* Replace the contents of the `bootstrap.php` file in the project's root directory with the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/bootstrap.php). Modify the list of configuration
  options with the file appropriately.
* Rename the `basepath` option within `.env` file to `basehref`.
* Add a `debug` option to `.env` with either `true` or `false` as its value.
* Add the following line to `.gitignore`:
  ```gitignore
  /var/cache/templates/**
  ```
* Replace the contents of the `psalm.xml` file in the project's root directory with the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/psalm.xml).