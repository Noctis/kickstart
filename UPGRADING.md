# Upgrading

This document talks about upgrading between different versions of Kickstart.

## From 1.4.2 to 2.0.0

Starting with version 2.0, Kickstart has been split into two packages - the system and user parts. That means that
some files were moved to a different location, while some were changed.

Upgrade process from `1.4.2` to `2.0.0` is pretty straightforward, but there are a couple of files that need to be 
modified by hand, i.e. it's not possible to just copy over their contents from the `2.0.0` branch.

### Dependencies

* Remove the reference to the `git@bitbucket.org:NoctisPL/database-lib.git` repository in `composer.json`.
* Run the following command to remove some packages:
  ```shell
  composer remove \
  ext-pdo \
  nikic/fast-route \
  noctis/database-lib \
  php-di/php-di \
  psr/http-server-middleware \
  twig/twig \
  vlucas/phpdotenv \
  vimeo/psalm
  ```
* Run the following commands to install new packages & update existing:
  ```shell
  composer update symfony/service-contracts symfony/polyfill-mbstring
  composer require --ignore-platform-reqs \
  php:^8.0 \
  composer-runtime-api:^2 \
  php-di/php-di:^6.3 \
  symfony/console:^5.2 \
  symfony/http-foundation:^5.2 \
  psr/http-message:^1.0 \
  psr/http-server-handler:^1.0
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
* Remove the reference to `App\Provider\DummyServicesProvider` from the `src/Console/Application.php` file.
  
### HTTP

* Delete the `src/Http/Action/BaseAction.php` file.
* Edit any `*Action.php` files within `src/Http/Action` directory and make sure:
  * those classes extend the `Noctis\KickStart\Http\Action\AbstractAction` abstract class,
  * their `execute()` methods type-hint returning either `HtmlResponse`, `RedirectResponse` or `EmptyResponse` from
    the `Laminas\Diactoros\Response` namespace, e.g. `Laminas\Diactoros\Response\HtmlResponse`,
  * if given action sends an attachment (i.e. file) in response, you can use the `sendFile()` action. 
* Delete the `src/Http/Factory`, `src/Http/Helper` directories.
* Delete the `ActionInvoker.php`, `RequestHandlerInterface.php` and `RequestHandlerStack.php` files from the
  `src/Http/Middleware` directory.
* Delete the `src/Http/Middleware/Guard/GuardMiddlewareInterface.php` file.
* Edit any `*Guard.php` files in the `src/Http/Middleware/Guard` directory and:
  * make sure those classes extend (not implement!) the `Noctis\KickStart\Http\Middleware\AbstractMiddleware` abstract 
    class,
  * remove the reference to the `App\Http\Helper\HttpRedirectionTrait` trait,
  * if the guard has its own constructor declared, make sure their parent's class, i.e. 
    `Noctis\KickStart\Http\Middleware\AbstractMiddleware` constructor gets an instance of 
    `Noctis\KickStart\Http\Response\ResponseFactoryInterface`
  * change the signature of the `process()` method from:
  ```php
  use App\Http\Middleware\RequestHandlerInterface;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  
  public function process(Request $request, RequestHandlerInterface $handler): Response
  {
      //...
  }
  ```
  to:
  ```php
  use Psr\Http\Message\ResponseInterface;
  use Psr\Http\Message\ServerRequestInterface;
  use Psr\Http\Server\RequestHandlerInterface;
  
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
      //...
  }
  ```
* Delete the `src/Http/Request/AbstractRequest.php` file.
* Edit any `*Request.php` files in the `src/Http/Request` directory and make sure:
  * those classes extend the `Noctis\KickStart\Http\Request\Request` class,
  * replace reference to `Symfony\Component\HttpFoundation\Request` with a reference to 
    `Psr\Http\Message\ServerRequestInterface`
* Move the `src/Http/Routes/StandardRoutes.php` file to `src/Http/Routing/routes.php`.
* Modify the `src/Http/Routing/routes.php` appropriately. For example, if the file originally looked like this:
  ```php
  <?php declare(strict_types=1);
  namespace App\Http\Routes;

  use App\Http\Action\DummyAction;
  use App\Http\Middleware\Guard\DummyGuard;
  use FastRoute\RouteCollector;

  final class StandardRoutes
  {
      public function get(): callable
      {
          return function (RouteCollector $r): void {
              $r->addGroup(
                  getenv('basepath'),
                  function (RouteCollector $r) {
                      $r->get('[{name}]', [
                          DummyAction::class,
                          [
                              DummyGuard::class,
                          ],
                      ]);
                  }
              );
          };
      }
  }
  ```
  it should now look like this:
  ```php
  <?php

  declare(strict_types=1);

  use App\Http\Action\DummyAction;
  use App\Http\Middleware\Guard\DummyGuard;

  return [
      ['GET', '[{name}]', DummyAction::class, [DummyGuard::class]],
  ];
  ```
* Delete the `src/Http/Router.php` file.
* Copy the `Application.php` file from
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Http/Application.php) to the `src/Http`
  directory.
* Remove the reference to `App\Provider\DummyServicesProvider` from the `src/Http/Application.php` file.
* Edit the `src/Http/Application.php` file and add any missing references to service provides - you can find a list of
  them in the `src/ContainerBuilder.php` file.
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

* Delete the `src/Provider/HttpServicesProvider.php`, `src/Provider/ServicesProviderInterface.php` and 
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
* Check your service providers to see if the definitions inside them need to be updated appropriately for the definition 
  format change in `2.0.0`. For example, the following definition, where a constructor parameter value is explicitly 
  defined:
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
* If your application has additional service providers, make sure to add a reference to them (as required) to the
  `src/Console/Application.php` and `src/Http/Application.php` files.

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
* Replace the contents of the `bootstrap.php` file in the project's root directory with the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/bootstrap.php). Copy the list of configuration 
  options from the `src/Configuration.php` file.
* Delete the `src/Configuration.php` and `src/ContainerBuilder.php` files.
* Replace the contents of the `templates/layout.html.twig` file with the 
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/templates/layout.html.twig). Be sure to check
  its contents and restore any custom changes that were there beforehand!
* Create the following directory path: `var/cache/templates` in the project's root directory.
* Replace all calls to `getenv('BASEDIR')` and `$_ENV['BASEDIR']` in your application with calls to `$_ENV['basepath']`.
* Rename the `basepath` option within `.env` file to `basehref`.
* Add a `debug` option to `.env` with either `true` or `false` as its value.
* Add the following line to `.gitignore`:
  ```gitignore
  /var/cache/templates/**
  ```
* Replace the contents of the `psalm.xml` file in the project's root directory with the
  [`2.0.0` version](https://github.com/Noctis/kickstart-app/blob/master/psalm.xml.dist).
