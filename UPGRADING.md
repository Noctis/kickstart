# Upgrading Guide

This document talks about upgrading between different versions of Kickstart.

* From 2.3.0:
  * [From 2.3.0 to 3.0.0](#from-230-to-300)
* From 1.4.2:
  * [From 1.4.2 to 3.0.0](#from-142-to-300)
  * [From 1.4.2 to 2.3.0](#from-142-to-230)

## From 2.3.0 to 3.0.0

Please refer to the [Upgrading Guide](https://github.com/Noctis/kickstart-app/blob/master/UPGRADING.md) in the
`noctis/kickstart-app` project.

## From 1.4.2 to 3.0.0

Starting with version 2.0, Kickstart has been split into two packages - the system and user parts. That means that
some files were moved to a different location, while some were changed.

Upgrade process from `1.4.2` to `3.0.0` is pretty straightforward, but there are a couple of files that need to be
modified by hand, i.e. it's not possible to just copy over their contents from the `3.0.0` branch.

### 1. Dependencies

* Remove the reference to the `git@bitbucket.org:NoctisPL/database-lib.git` repository in `composer.json`.
* Run the following commands to remove packages:
  ```shell
  composer remove \
  ext-pdo \
  nikic/fast-route \
  noctis/database-lib \
  php-di/php-di \
  psr/http-server-middleware \
  symfony/console \
  symfony/http-foundation \
  twig/twig \
  vlucas/phpdotenv \
  symfony/var-dumper \
  vimeo/psalm
  ```
* Run the following commands to install new packages:
  ```shell
  composer require \
  php:^8.0 \
  composer-runtime-api:^2 \
  noctis/kickstart:^3.0 \
  paragonie/easydb:^2.11 \
  php-di/php-di:^6.3 \
  psr/container:^1.1 \
  psr/http-message:^1.0 \
  psr/http-server-handler:^1.0 \
  symfony/console:^5.3 \
  symfony/http-foundation:^5.3 \
  vlucas/phpdotenv:^5.3
  
  composer require --dev \
  roave/security-advisories:dev-latest \
  squizlabs/php_codesniffer:^3.6 \
  symfony/var-dumper:^5.3 \
  vimeo/psalm:^4.9
  ```
* Since Psalm has been updated from 3.x to 4.x, and the new version's configuration file (`psalm.xml`) has a different
  format, run the following command to recreate it:
  ```shell
  mv psalm.xml psalm.xml.bak && vendor/bin/psalm --init
  ```

### 2. The Basics

* Replace the following lines in the `.env-example`:
  ```dotenv
  # Make sure it ends with a slash (/)!
  basepath=/
  ```
  with the following lines:
  ```dotenv
  # Valid values: prod, dev
  APP_ENV=dev
  # "/" for root-dir, "/foo" (without trailing slash) for sub-dir
  basehref=/
  ```
  * modify the `.env` file appropriately; **notice that the `basepath` option has been renamed to `basehref`!**
  * **in a production environment, you want to set the `APP_ENV` option in `.env` to `prod`**.
* Replace the contents of the `bootstrap.php` file in the project's root directory with the
  [`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/bootstrap.php). Copy the list of configuration
  options from the `src/Configuration.php` file.
* Delete the `src/Configuration.php` file.
* Replace all the calls to `getenv('BASEDIR')` and `$_ENV['BASEDIR']` in your application with calls to
  `$_ENV['basepath']`.
* Copy the `src/Configuration` directory from the
  [`3.0` version](https://github.com/Noctis/kickstart-app/tree/master/src/Configuration) into the project's root
  directory.
* Copy the `src/Database` directory from the
  [`3.0` version](https://github.com/Noctis/kickstart-app/tree/master/src/Database) into the project's root directory.
* Create the following directory path: `var/cache/container` in the project's root directory. Create an empty file
  called `.empty` inside it so that the directory can be committed into the VCS.
* Add the following lines to `.gitignore`:
  ```gitignore
  /var/cache/container/**
  !/var/cache/container/.empty
  ```

### 3. Service Providers

* Delete the `src/Provider/HttpServicesProvider.php`, `src/Provider/ServicesProviderInterface.php` and
  `src/Provider/TwigServiceProvider.php` files.
* Remove references to `App\Provider\HttpServicesProvider` and `App\Provider\TwigServiceProvider` providers from the
  `src/ContainerBuilder.php` file.
* Edit any Service Provider files within `src/Provider` directory and make sure those classes implement the
  `Noctis\KickStart\Provider\ServicesProviderInterface` interface.
* Check your service providers to see if the definitions inside them need to be updated appropriately for the definition
  format change introduced in Kickstart `2.0`. For example, the following definition, where a constructor parameter 
  value is explicitly defined:
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
          $_ENV['dummy_param'] === true
      ),
  ```
* Replace the contents of the `src/Provider/DatabaseConnectionProvider.php` file with contents of the
  [`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Provider/DatabaseConnectionProvider.php).
  If there were additional database connections defined there, you will need to transpose them appropriately, based on
  the primary database connection definition.
* Copy the [`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Provider/ConfigurationProvider.php)
  of the `ConfigurationProvider.php` file into the `src/Provider` directory.

### 4. HTTP Related Things

* Replace the contents of the `templates/layout.html.twig` file with the
  [`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/templates/layout.html.twig). **Be sure to check
  its contents and restore any custom changes that were there beforehand!**
* Create the following directory path: `var/cache/templates` in the project's root directory. Create an empty file
  called `.empty` inside it so that the directory can be committed into the VCS.
* Add the following lines to `.gitignore`:
  ```gitignore
  /var/cache/templates/**
  !/var/cache/templates/.empty
  ```
  
#### 4.1 HTTP Middlewares

* Edit any `*Guard.php` files in the `src/Http/Middleware/Guard` directory and make sure those classes implement the 
  `Psr\Http\Server\MiddlewareInterface` interface.
* Change the signature of the `process()` method from:
  ```php
  <?php 

  declare(strict_types=1);

  namespace App\Http\Middleware\Guard;

  use App\Http\Middleware\RequestHandlerInterface;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;

  final class DummyGuard implements GuardMiddlewareInterface
  {
      // ...

      public function process(Request $request, RequestHandlerInterface $handler): Response
      {
          // ...
      }
  }
  ```
  to (**notice the change to `RequestHandlerInterface` import line!**):
  ```php
  <?php

  declare(strict_types=1);

  namespace App\Http\Middleware\Guard;

  use Psr\Http\Message\ResponseInterface;
  use Psr\Http\Message\ServerRequestInterface;
  use Psr\Http\Server\MiddlewareInterface;
  use Psr\Http\Server\RequestHandlerInterface;

  final class DummyGuard implements MiddlewareInterface
  {
      // ...

      public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
      {
          // ...
      }
  }
  ```
* If the middleware uses the `App\Http\Helper\HttpRedirectionTrait` trait:
  * remove any references to it in the middleware,
  * make sure an instance of `Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface` is injected
    into the middleware, through its constructor,
  * use the `RedirectResponseFactoryInterface::toPath()` method, in place of calls to 
    `HttpRedirectionTrait::redirect()`.
* If the middleware uses the `App\Http\Helper\FlashMessageTrait` trait:
  * remove any references to it in the middleware,
  * make sure an instance of `Noctis\KickStart\Http\Service\FlashMessageServiceInterface` is injected into the 
    middleware, through its constructor,
  * use the `FlashMessageServiceInterface::setFlashMessage()` and `FlashMessageServiceInterface::getFlashMessage()`
    methods, in place of calls to `FlashMessageTrait::setFlashMessage()` and `FlashMessageTrait::getFlashMessage()`
    methods, respectively.
* If the middleware uses the `App\Http\Helper\RenderTrait` trait:
  * remove any references to it and `Twig\Environment` in the middleware,
  * make sure an instance of `Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface` is injected into the
    middleware, through its constructor,
  * use the `HtmlResponseFactoryInterface::render()` method, in place of calls to `RenderTrait::render()`.
* Delete the `src/Http/Middleware/Guard/GuardMiddlewareInterface.php` file.

#### 4.2 HTTP Actions

* Rename & move the `src/Http/Routes/StandardRoutes.php` file to `src/Http/Routing/routes.php`.
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
  use Noctis\KickStart\Http\Routing\Route;

  return [
      new Route('GET', '/[{name}]', DummyAction::class, [DummyGuard::class]),
  ];
  ```
* Delete the `src/Http/Router.php` file.
* Replace the contents of the `public/index.php` file in the project's root directory with the
  [`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/public/index.php).
* Edit the `public/index.php` file and:
  * copy over list of service provides from the `src/ContainerBuilder.php` file and use calls to
    `Noctis\KickStart\Http\ContainerBuilder::registerServicesProvider()` method to register them,
  * remove registrations of any service provider that do not exist,
  * make sure `App\Provider\ConfigurationProvider` is on the list.
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
* Delete the `src/Http/Request/AbstractRequest.php` file.
* Edit any `*Request.php` files in the `src/Http/Request` directory and make sure:
  * those classes extend the `Noctis\KickStart\Http\Request\Request` class,
  * replace reference to `Symfony\Component\HttpFoundation\Request` with a reference to
    `Psr\Http\Message\ServerRequestInterface`
  * make sure the parent class receives an instance of `Laminas\Session\ManagerInterface` through its constructor:
    ```php
    public function __construct(ServerRequestInterface $request, ManagerInterface $sessionManager)
    {
        parent::__construct($request, $sessionManager);
    }
    ```
* Edit any `*Action.php` files within `src/Http/Action` directory (except `BaseAction.php`) and:
  * make sure those classes implement the `Noctis\KickStart\Http\Action\ActionInterface` interface,
  * rename their `execute()` methods to `process()` with the following signature:
    ```php
    <?php

    declare(strict_types=1);

    namespace App\Http\Action;

    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    
    // ...
    
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // ...
    }
    ```,
  * if the `execute()` method had dependencies injected into it, eg:
    ```php
    <?php declare(strict_types=1);
    namespace App\Http\Action;
  
    use App\Http\Request\DummyRequest;
    use Symfony\Component\HttpFoundation\Response;
  
    final class DummyAction extends BaseAction
    {
        public function execute(DummyRequest $request): Response
        {
            // ...
        }
    }
    ``` 
    inject them into the action through the action's constructor, eg:
    ```php
    <?php 

    declare(strict_types=1);
  
    namespace App\Http\Action;
  
    use App\Http\Request\DummyRequest;
    use Noctis\KickStart\Http\Action\ActionInterface;
    // ...
  
    final class DummyAction implements ActionInterface
    {
        private DummyRequest $request;

        public function __construct(DummyRequest $request)
        {
            $this->request = $request;
        }
    
        // ...
    }
    ```
  * if the action has a custom HTTP request object injected into it, make sure to change all the calls to `$request` in
    the `process()` method, to calls to `$this->request`,
  * make sure `process()` method type-hints returning either `HtmlResponse`, `RedirectResponse`, `JsonResponse` or 
    `EmptyResponse` from the `Laminas\Diactoros\Response` namespace, e.g. `Laminas\Diactoros\Response\HtmlResponse`.
* If the given action returns an HTML response, i.e. has the `return $this->render(...);` line:
  * make sure an instance of `Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface` is injected into it,
  * replace the call to `$this->render()` with a call to the `HtmlResponseFactoryInterface::render()` method.
* If the given action returns a redirection, i.e. has the `return $this->redirect(...);` line:
  * make sure an instance of `Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface` is injected into
    it,
  * replace the call to `$this->redirect()` with a call to the `RedirectResponseFactoryInterface::toPath()` method.
* If the given action returns (sends) a file in response:
  * make sure an instance of `Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface` is injected
    into it,
  * call one the following methods to return an instance of `Noctis\KickStart\Http\Response\AttachmentResponse`:
    * `AttachmentResponseFactoryInterface::sendFile()`,
    * `AttachmentResponseFactoryInterface::sendContent()`, or
    * `AttachmentResponseFactoryInterface::sendResource()`.
* If the given action use the flash messages functionality, i.e. has the `$this->setFlashMessage()` and/or
  `$this->getFlashMessage()` lines:
  * make sure an instance of `Noctis\KickStart\Http\Service\FlashMessageServiceInterface` is injected into it,
  * replace the calls to `$this->setFlashMessage()` and `$this->getFlashMessage()` methods, with calls to
    `FlashMessageServiceInterface::setFlashMessage()` and `FlashMessageServiceInterface::getFlashMessage()` methods
    respectively.
* If there were no custom methods added to the `App\Http\Action\BaseAction` abstract class, delete the
  `src/Http/Action/BaseAction.php` file.
* Delete the `src/Http/Factory`, `src/Http/Helper` directories.
* Delete the `ActionInvoker.php`, `RequestHandlerInterface.php` and `RequestHandlerStack.php` files from the
  `src/Http/Middleware` directory.

### 5. Console Related Things

* Replace the contents of the `bin/console` file in the project's root directory with the
  [`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/bin/console).
* Edit the `bin/console` file:
  * copy over list of service provides from the `src/ContainerBuilder.php` file and use calls to
    `Noctis\KickStart\Http\ContainerBuilder::registerServicesProvider()` method to register them,
  * remove registrations of any HTTP-related (eg: `App\Provider\HttpMiddlewareProvider`) or non-existent service
    providers,
  * make sure `App\Provider\ConfigurationProvider` is on the list,
  * make sure to restore any references to console commands classes (classes from the `App/Console/Command` namespace)
    from the original file, so that those commands can still be called from the CLI.
* Edit any `*Command.php` files within `src/Console/Command` directory (except `BaseCommand.php`) and make sure those
  classes extend the `Noctis\KickStart\Console\Command\AbstractCommand` abstract class.
* Remove the `src/Console/Command/BaseCommand.php` file.

### 6. Repositories

* Rename the `DatabaseRepository` class in the `src/Repository` directory to `AbstractDatabaseRepository` and replace
  its contents with the contents of its
  [`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Repository/AbstractDatabaseRepository.php)
  version.
* Edit any `*Repository.php` files inside the `src/Repository` (except `AbstractDatabaseRepository.php`) directory and
  make sure those classes extend the local `AbstractDatabaseRepository` abstract class.

### 7. What's Left

* Delete the `src/ContainerBuilder.php` file.
* Add the following section to your `composer.json`:
  ```json
  "extra": {
      "app-version": "3.0.0"
  }
  ```
  and run the following command to update your `composer.lock` file:
  ```shell
  composer update --lock
  ```

## From 1.4.2 to 2.3.0

Starting with version 2.0, Kickstart has been split into two packages - the system and user parts. That means that
some files were moved to a different location, while some were changed.

Upgrade process from `1.4.2` to `2.3.0` is pretty straightforward, but there are a couple of files that need to be 
modified by hand, i.e. it's not possible to just copy over their contents from the `2.3.0` branch.

### 1. Dependencies

* Remove the reference to the `git@bitbucket.org:NoctisPL/database-lib.git` repository in `composer.json`.
* Run the following commands to remove packages:
  ```shell
  composer remove \
  ext-pdo \
  nikic/fast-route \
  noctis/database-lib \
  php-di/php-di \
  psr/http-server-middleware \
  symfony/console \
  symfony/http-foundation \
  twig/twig \
  vlucas/phpdotenv \
  symfony/var-dumper \
  vimeo/psalm
  ```
* Run the following commands to install new packages:
  ```shell
  composer require \
  php:^8.0 \
  composer-runtime-api:^2 \
  laminas/laminas-diactoros:^2.5 \
  noctis/kickstart:^2.3 \
  paragonie/easydb:^2.11 \
  php-di/php-di:^6.3 \
  psr/container:^1.0 \
  psr/http-message:^1.0 \
  psr/http-server-handler:^1.0 \
  symfony/console:^5.2 \
  symfony/http-foundation:^5.2

  composer require --dev \
  roave/security-advisories:dev-latest \
  symfony/var-dumper:^5.2 \
  vimeo/psalm:^4.4
  ```
* Since Psalm has been updated from 3.x to 4.x, and the new version's configuration file (`psalm.xml`) has a different
  format, run the following command to recreate it:
  ```shell
  mv psalm.xml psalm.xml.bak && vendor/bin/psalm --init
  ```
  
### 2. The basics

* Add a `debug` option to `.env` with either `true` or `false` as its value. Add `debug=false` line to the 
  `.env-example` file.
* Rename the `basepath` option in your `.env` and `.env-example` files to `basehref`.
* Replace the contents of the `bootstrap.php` file in the project's root directory with the
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/bootstrap.php). Copy the list of configuration
  options from the `src/Configuration.php` file. Remember to include the `'debug' => 'required,bool'` and
  `'basehref' => 'required'` lines in the list there. Remember that the `basepath` option has been renamed to
  `basehref`.
* Delete the `src/Configuration.php` file.
* Replace all the calls to `getenv('BASEDIR')` and `$_ENV['BASEDIR']` in your application with calls to 
  `$_ENV['basepath']`.
* Copy the `src/Configuration` directory from the
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/tree/2.3.0/src/Configuration) into the project's root
  directory.
* Copy the `src/Database` directory from the
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/tree/2.3.0/src/Database) into the project's root directory.
  
### 3. Service Providers

* Delete the `src/Provider/HttpServicesProvider.php`, `src/Provider/ServicesProviderInterface.php` and
  `src/Provider/TwigServiceProvider.php` files.
* Edit any Service Provider files within `src/Provider` directory and make sure those classes implement the
  `Noctis\KickStart\Provider\ServicesProviderInterface` interface.
* Check your service providers to see if the definitions inside them need to be updated appropriately for the definition
  format change in Kickstart `2.0`. For example, the following definition, where a constructor parameter value is
  explicitly defined:
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
* Replace the contents of the `src/Provider/DatabaseConnectionProvider.php` file with contents of the
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/src/Provider/DatabaseConnectionProvider.php).
  If there were additional database connections defined there, you will need to transpose the appropriately, based on
  the primary database connection definition.
* Copy the [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/src/Provider/ConfigurationProvider.php)
  of the `ConfigurationProvider.php` file into the `src/Provider` directory.

### 4. HTTP Related Things

* Replace the contents of the `templates/layout.html.twig` file with the
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/templates/layout.html.twig). **Be sure to check
  its contents and restore any custom changes that were there beforehand!**
* Create the following directory path: `var/cache/templates` in the project's root directory. Create an empty file
  called `.empty` inside it so that the directory can be committed into the VCS.
* Add the following lines to `.gitignore`:
  ```gitignore
  /var/cache/templates/**
  !/var/cache/templates/.empty
  ```
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
* Delete the `src/Http/Middleware/Guard/GuardMiddlewareInterface.php` file.
* Rename & move the `src/Http/Routes/StandardRoutes.php` file to `src/Http/Routing/routes.php`.
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
  use Noctis\KickStart\Http\Routing\Route;

  return [
      new Route('GET', '[{name}]', DummyAction::class, [DummyGuard::class]),
  ];
  ```
* Delete the `src/Http/Router.php` file.
* Copy the `Application.php` file from
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/src/Http/Application.php) to the `src/Http`
  directory.
* Edit the `src/Http/Application.php` file:
  * copy over list of service provides from the `src/ContainerBuilder.php` file,
  * remove `App\Provider\HttpServicesProvider` from the list and any other service provider (e.g. 
    `App\Provider\DummyServicesProvider`) that does not exist,
  * make sure `App\Provider\ConfigurationProvider` is on the list.
* Edit the `public/index.php` file. Replace the contents of this file with the
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/public/index.php).
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
* Delete the `src/Http/Request/AbstractRequest.php` file.
* Edit any `*Request.php` files in the `src/Http/Request` directory and make sure:
  * those classes extend the `Noctis\KickStart\Http\Request\Request` class,
  * replace reference to `Symfony\Component\HttpFoundation\Request` with a reference to
    `Psr\Http\Message\ServerRequestInterface`
* Edit any `*Action.php` files within `src/Http/Action` directory (except `BaseAction.php`) and:
  * make sure those classes implement the `Noctis\KickStart\Http\Action\ActionInterface` interface,
  * have the following constructor:
    ```php
    use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
    
    // ...
    
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }
    ```
  * rename their `execute()` methods to `process()` with the following signature:
    ```php
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    
    // ...
    
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // ...
    }
    ```,
  * if an `execute()` method had dependencies injected into it, move them to the action's constructor,
  * if an action uses the `$this->render()` method, replace it with a call to `$this->responseFactory->htmlResponse()`,
  * if an action uses the `$this->redirect()` method, replace it with a call to 
    `$this->responseFactory->redirectionResponse()`,
  * if given action sends an attachment (i.e. file) in response, you can use the `sendAttachment()` action,
  * make sure `process()` method type-hints returning either `HtmlResponse`, `RedirectResponse`, `JsonResponse` or
    `EmptyResponse` from the `Laminas\Diactoros\Response` namespace, e.g. `Laminas\Diactoros\Response\HtmlResponse`.
* If there are no custom methods inside, delete the `src/Http/Action/BaseAction.php` file.
* Delete the `src/Http/Factory`, `src/Http/Helper` directories.
* Delete the `ActionInvoker.php`, `RequestHandlerInterface.php` and `RequestHandlerStack.php` files from the
  `src/Http/Middleware` directory.

### 5. Console Related Things

* Copy the `Application.php` file from
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/src/Console/Application.php) to the
  `src/Console` directory.
* Edit the `src/Console/Application.php` file:
  * copy over list of service provides from the `src/ContainerBuilder.php` file,
  * remove `App\Provider\HttpServicesProvider`, `App\Provider\HttpMiddlewareProvider` and 
    `App\Provider\TwigServiceProvider` from the list, and any other service provider (e.g.
    `App\Provider\DummyServicesProvider`) that does not exist,
  * make sure `App\Provider\ConfigurationProvider` is on the list.
* Edit the `bin/console` file. Replace the contents of the file with contents of the
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/bin/console). Make sure to copy over any
  references to console commands classes (classes from the `App/Console/Command` namespace) from the original file,
  so that those commands can still be called from the CLI.  
* Edit any `*Command.php` files within `src/Console/Command` directory (except `BaseCommand.php`) and make sure those 
  classes extend the `Noctis\KickStart\Console\Command\AbstractCommand` abstract class.
* Remove the `src/Console/Command/BaseCommand.php` file.

### 6. Repositories

* Rename the `DatabaseRepository` class in the `src/Repository` directory to `AbstractDatabaseRepository` and replace
  its contents with the contents of its 
  [`2.3.0` version](https://github.com/Noctis/kickstart-app/blob/2.3.0/src/Repository/AbstractDatabaseRepository.php)
  version.
* Edit any `*Repository.php` files inside the `src/Repository` directory (except `AbstractDatabaseRepository.php`) and
  make sure those classes extend the local `AbstractDatabaseRepository` abstract class.

### 7. What's Left

* Delete the `src/ContainerBuilder.php` file.
