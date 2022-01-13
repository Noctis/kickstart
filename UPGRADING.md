# Upgrading Guide

This document will tell you how to upgrade your application between different versions of Kickstart.

* From 2.3.0:
  * [From 2.3.0 to 3.0.0](#from-230-to-300)
* From 1.4.2:
  * [From 1.4.2 to 3.0.0](#from-142-to-300)
  * [From 1.4.2 to 2.3.0](#from-142-to-230)

## From 2.3.0 to 3.0.0

Upgrading from a Kickstart 2.3.0-based application to a Kickstart 3.0.0 is easier than it may seem. There will be a
couple of things that you'll need to do, all of them explained in detail below:

* minor update to `.env` (and `.env-example`),
* update to `boostrap.php` file,
* update to the configuration class (`App\Configuration\FancyConfiguration`),
* updating routes list in `src/Http/Routing/routes.php` file to the new format, 
* update to application's entry points: the `public/index.php` and `bin/console` files,
* updates to HTTP middleware and actions

### 1. Upgrading Dependencies

Run the following commands to upgrade your application's dependencies:

```shell
  composer require --with-all-dependencies \
  php:~8.0.0 \
  noctis/kickstart:^3.0@dev \
  laminas/laminas-diactoros:^2.8 \
  psr/container:^1.1 \
  psr/http-server-middleware:^1.0 \
  symfony/console:^5.3 \
  symfony/http-foundation:^5.3 \
  vlucas/phpdotenv:^5.3
```

```shell
  composer require --dev --with-all-dependencies \
  squizlabs/php_codesniffer:^3.6 \
  symfony/var-dumper:^5.3 \
  vimeo/psalm:^4.11
  ```

### 2. Updating `.env` and `.env-example`

Regarding the `.env` and `.env-example` files, only one thing has changed between Kickstart 2.3.0 and 3.0.0: the
`debug` option, with its `true` or `false` values has been replaced by `APP_ENV`, with `prod` or `dev` values.

Replace the:
```dotenv
debug=...
```

line in your `.env` and `.env-example` files with the following lines:
```dotenv
# Valid values: prod, dev
APP_ENV=...
```

Change its value accordingly:

| Before:       | After:         |
| ------------- | -------------- |
| `debug=false` | `APP_ENV=prod` |
| `debug=true`  | `APP_ENV=dev`  |

### 3. Migrating `bootstrap.php`

One major thing that has changed in the `boostrap.php` file between Kickstart 2.3.0 and 3.0.0, is the way that the 
configuration options requirements are defined.

Replace the contents of your application's `boostrap.php` file with the 
[`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/bootstrap.php).

Now you'll need to migrate your configuration requirements list to the new "format". In Kickstart 2.3.0, the 
requirements were passed as an array:

```php
(new ConfigurationLoader())
    ->load(__DIR__, [
        'basehref' => 'required',
        'db_host'  => 'required',
        'db_user'  => 'required',
        'db_pass'  => 'required',
        'db_name'  => 'required',
        'db_port'  => 'required,int',
    ]);
```

In Kickstart 3.0.0 you are given access to the `Dotenv\Dotenv` instance, so you need to follow its rules:

* all the required options need to be passed, as an array, to the `Dotenv::required()` method:
  ```php
  $dotenv->required([
      'basehref',
      'db_host',
      'db_user',
      'db_pass',
      'db_name',
      'db_port'
  ]);
  ```
* if an option's value needs to be an integer, use the `Dotenv::isInteger()` method:
  ```php
  $dotenv->required('db_port')
      ->isInteger();
  ```
* if an option's value needs to be boolean, use the `Dotenv::isBoolean()` method:
  ```php
  $dotenv->required('banning_enabled')
      ->isBoolean();
  ```
  
Remember that the `debug` option has been replaced with the `APP_ENV` one, and is no longer boolean.

### 4. Migrating Configuration Classes

There were two major changes regarding configuration classes between Kickstart 2.3.0 and 3.0.0:

* all the methods offered by the `Noctis\KickStart\Configuration\Configuration` class are now static, and the class
  itself is no longer instantiable,
* the `Noctis\KickStart\Configuration\ConfigurationInterface` interface has been removed.

First thing you should do is edit your application's `App\Configuration\FancyConfiguration` class and:

* remove `Noctis\KickStart\Configuration\ConfigurationInterface` as its injected dependency,
* remove its `getBaseHref()`, `get()`, `set()` and `has()` methods,
* replace all the calls to its `get()` method (inside the class and elsewhere in your application), with calls to the 
  `Configuration::get()` static method, for example replace this:
  ```php
  public function getDBHost(): string
  {
      /** @var string */
      return $this->get('db_host');
  }
  ```
  with this:
  ```php
  public function getDBHost(): string
  {
      /** @var string */
      return Configuration::get('db_host');
  }
  ```

Check your application for other places where the `ConfigurationInterface` methods, i.e. `get()`, `set()`, `has()` or 
`getBaseHref()` are called and replace those calls with calls to their static equivalents. For example, replace this:

```php
$basePath = $this->configuration
    ->get('basepath');
```

with this:
```php
$basePath = Configuration::get('basepath');
```

Replace the contents of your `App\Provider\ConfigurationProvider` service provider class with the
[`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/src/Provider/ConfigurationProvider.php).

Lastly, make sure your application's `App\Configuration\FancyConfigurationInterface` interface no longer extends the
now defunct `Noctis\KickStart\Configuration\ConfigurationInterface` one.

### 5. DIC Compilation

One major thing introduced in Kickstart 3.0.0 is that the DIC (Dependency Injection Container) can now compile its
configuration and save it to a file, which greatly speeds up the dependency resolution process. For it to work, DIC
needs a folder into which it will save the compiled configuration files. 

By default, that folder is `var/cache/container`, inside the root folder of your application. Create such a folder and
then create an empty file called `.empty` inside it (so that the directory can be committed). Lastly, add
the following lines to `.gitignore`, so that the compiled DIC files won't make their way into your VCS repository:

```gitignore
/var/cache/container/**
!/var/cache/container/.empty
```

### 6. Migrating to New Routes List Format

The HTTP routes list file - `src/Http/Routing/routes.php` - has seen a format upgrade in Kickstart 3.0.0. A single
route is now a `Noctis\KickStart\Http\Routing\Route` object, instead of an array.

To migrate your routes list, edit the `src/Http/Routing/routes.php` file and edit the routes accordingly, for example 
replace this:

```php
<?php

declare(strict_types=1);

use App\Http\Action\DummyAction;
use App\Http\Middleware\Guard\DummyGuard;

return [
    ['GET', '/', DummyAction::class, [DummyGuard::class]],
];
```

with this:
```php
<?php

declare(strict_types=1);

use App\Http\Action\DummyAction;
use App\Http\Middleware\Guard\DummyGuard;
use Noctis\KickStart\Http\Routing\Route;

return [
    Route::get('/', DummyAction::class, [DummyGuard::class]),
];
```

The `Route` class offers two static factory methods:

* `Route::get()` for routes available _via_ HTTP `GET` method,
* `Route::post()` for routes available _via_ HTTP `POST` method.

### 7. Migrating Application's Entry Points

A standard Kickstart application has two entry points:

* `public/index.php` file, for Web-based applications, and
* `bin/console` for console-based (CLI) applications.

#### 7.1 HTTP Entry Point

The Web entry point in a Kickstart application is the `public/index.php` file. Two major things changed between
versions 2.3.0 and 3.0.0:

* the list of service providers for a web application has been moved from the `src/Http/Application.php` file to
  `public/index.php`,
* the list of routes (`src/Http/Routing/routes.php` file) is now passed to the
  `Noctis\KickStart\Provider\RoutingProvider`'s constructor, instead of the `App\Http\Application`'s one.

Start by replacing the contents of the `public/index.php` file with its
[`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/public/index.php).

Now lets deal with the service providers. Open the `src/Http/Application.php` file and make sure all the service
providers listed there, e.g.:

```php
// src/Http/Application.php

// ...

protected function getServiceProviders(): array
{
    return array_merge(
        parent::getServiceProviders(),
        [
            new ConfigurationProvider(),
            new DatabaseConnectionProvider(),
            new HttpMiddlewareProvider(),
            new DummyServicesProvider(),
            new RepositoryProvider(),
        ]
    );
}
```

are now properly being registered in the `public/index.php` file, _via_ the 
`ContainerBuilder::registerServicesProvider()` method:

```php
// public/index.php

// ...

$containerBuilder = new ContainerBuilder();
$containerBuilder
    ->registerServicesProvider(new RoutingProvider(
        require_once __DIR__ . '/../src/Http/Routing/routes.php'
    ))
    ->registerServicesProvider(new ConfigurationProvider())
    ->registerServicesProvider(new DatabaseConnectionProvider())
    ->registerServicesProvider(new HttpMiddlewareProvider())
    ->registerServicesProvider(new DummyServicesProvider())
    ->registerServicesProvider(new RepositoryProvider())
;
```

Once you're done with migrating service providers registration to `public/index.php`, delete the
`src/Http/Application.php` file.

#### 7.2 CLI Entry Point

The console (CLI) entry point in a Kickstart application is the `bin/console` file. Two major things changed between 
version 2.3.0 and 3.0.0:

* the list of service providers for a console application has been moved from `src/Console/Application.php` file to 
  `bin/console`,
* the way in which console commands are registered.

Start by replacing the contents of the `bin/console` file with its 
[`3.0` version](https://github.com/Noctis/kickstart-app/blob/master/bin/console). 

Now, lets deal with the service providers. Open the `src/Console/Application.php` file and make sure all the service 
providers listed there, e.g.:

```php
// src/Console/Application.php

// ...

protected function getServiceProviders(): array
{
    return array_merge(
        parent::getServiceProviders(),
        [
            new ConfigurationProvider(),
            new DatabaseConnectionProvider(),
            new DummyServicesProvider(),
            new RepositoryProvider(),
        ]
    );
}
```

are now properly being registered in the `bin/console` file, _via_ the `ContainerBuilder::registerServicesProvider()`
method:

```php
// bin/console

// ...

$containerBuilder = new ContainerBuilder();
$containerBuilder
    ->registerServicesProvider(new ConfigurationProvider())
    ->registerServicesProvider(new DatabaseConnectionProvider())
    ->registerServicesProvider(new DummyServicesProvider())
    ->registerServicesProvider(new RepositoryProvider())
;
```

Once that's done, we can deal with migrating the way that console commands are registered. In Kickstart 2.3.0 console
commands were registered by passing an array of their class names to the `App\Console\Application` constructor:

```php
// bin/console

$app = new Application([
    DummyCommand::class
]);
$app->run();
```

In Kickstart 3.0.0 and up, the commands are registered by passing the same array to the `setCommands()` method of the 
`Noctis\KickStart\Console\ConsoleApplication` instance:

```php
// bin/console

/** @var ConsoleApplication $app */
$app = $container->get(ConsoleApplication::class);
$app->setCommands([
    DummyCommand::class
]);
```

If your application uses a custom commands loader, i.e. the `App\Console\Application::setCommandLoaderFactory()` method
is used, you'll need to switch to the `Noctis\KickStart\Console\ConsoleApplication::setCommandLoaderFactory()` method.
The new method's signature is exactly the same as the old one's.

Once you're done with migrating service providers registration and console commands in the `bin/console` file, delete 
the `src/Console/Application.php` file.

### 8. Migrating HTTP Actions

In Kickstart 3.0.0 two major changes to HTTP actions classes have been introduced:

* all actions must implement the `Noctis\KickStart\Http\Action\ActionInterface` interface,
* the `Noctis\KickStart\Http\Action\AbstractAction` abstract class has been removed.

Start by modifying all the HTTP actions which extend the `AbstractAction` to now implement the `ActionInterface` 
interface, i.e. replace this:

```php
<?php

declare(strict_types=1);

namespace App\Http\Action;

use Noctis\KickStart\Http\Action\AbstractAction;

final class DummyAction extends AbstractAction
{
    // ...
}
```

like so:

```php
<?php

declare(strict_types=1);

namespace App\Http\Action;

use Noctis\KickStart\Http\Action\ActionInterface;

final class DummyAction implements ActionInterface
{
    // ...
}
```

Next, replaced the `execute()` methods with the `process()` method, using the following signature:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
{
    // ...
}
```

If there were any dependencies injected through the `execute()` method, those will need to be moved to the action's
constructor class.

Because the `Noctis\KickStart\Http\Action\AbstractAction` abstract class has been removed, actions will no longer have 
access to certain helper functions, like `render()` or `redirect()`. Some of these functions - with the same signatures 
as in the `AbstractAction` - are available through traits now. You will need to switch your actions to using those 
traits and provide those traits with an instance of a specific response factory that they require.

#### 8.1 The `render()` Method

The `render()` method is now available through the `Noctis\KickStart\Http\Helper\RenderTrait`. This trait needs to be
provided an instance of `HtmlResponseFactoryInterface` into its private `$htmlResponseFactory` field:

```php
<?php

declare(strict_types=1);

namespace App\Http\Action;

use Laminas\Diactoros\Response\HtmlResponse;
use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Helper\RenderTrait;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DummyAction implements ActionInterface
{
    use RenderTrait;

    public function __construct(HtmlResponseFactoryInterface $htmlResponseFactory)
    {
        $this->htmlResponseFactory = $htmlResponseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): HtmlResponse
    {
        // ...

        return $this->render(/* ... */);
    }
}
```

#### 8.2 The `redirect()` Method

The `redirect()` method is now available through the `Noctis\KickStart\Http\Helper\RedirectTrait`. This trait needs to 
be provided an instance of `RedirectResponseFactoryInterface` into its private `$redirectResponseFactory` field:

```php
<?php

declare(strict_types=1);

namespace App\Http\Action;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Helper\RedirectTrait;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DummyAction implements ActionInterface
{
    use RedirectTrait;

    public function __construct(RedirectResponseFactoryInterface $redirectResponseFactory)
    {
        $this->redirectResponseFactory = $redirectResponseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): RedirectResponse
    {
        // ...
        
        return $this->redirect(/* ... */);
    }
}
```

#### 8.3 The `notFound()` Method

The `notFound()` method is now available through the `Noctis\KickStart\Http\Helper\NotFoundTrait`. This trait needs to
be provided an instance of `NotFoundResponseFactoryInterface` into its private `$notFoundResponseFactory` field:

```php
<?php

declare(strict_types=1);

namespace App\Http\Action;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;
use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Helper\NotFoundTrait;
use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DummyAction implements ActionInterface
{
    use NotFoundTrait;

    public function __construct(NotFoundResponseFactoryInterface $notFoundResponseFactory)
    {
        $this->notFoundResponseFactory = $notFoundResponseFactory;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): EmptyResponse | TextResponse {
        // ...

        return $this->notFound(/* ... */);
    }
}
```

#### 8.4 The `sendAttachment()` and `sendFile()` Methods

The `sendAttachment()` method is now available through the `Noctis\KickStart\Http\Helper\AttachmentTrait`. This trait 
needs to be provided an instance of `AttachmentResponseFactoryInterface` into its private `$attachmentResponseFactory` 
field:

```php
<?php

declare(strict_types=1);

namespace App\Http\Action;

use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Helper\AttachmentTrait;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DummyAction implements ActionInterface
{
    use AttachmentTrait;

    public function __construct(AttachmentResponseFactoryInterface $attachmentResponseFactory)
    {
        $this->attachmentResponseFactory = $attachmentResponseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AttachmentResponse
    {
        // ...

        return $this->sendAttachment(/* ... */);
    }
}
```

The `sendFile()`, as it has been deprecated in Kickstart 2.1.0, needs to be replaced by `sendAttachment()` method.

The `AttachmentTrait` does offer some additional factory methods for creating an instance of `AttachmentResponse`:

* `sendFile()` - can be used as a shortcut for `AttachmentResponseFactoryInterface::sendFile()`,
* `sendContent()` - can be used as a shortcut for `AttachmentResponseFactoryInterface::sendContent()`,
* `sendResource()` - can be used as a shortcut for `AttachmentResponseFactoryInterface::sendResource()`.

#### 8.5 The `setFlashMessage()` and `getFlashMessage()` Methods

The `setFlashMessage()` and `getFlashMessage()` methods are now available in the 
`Noctis\KickStart\Http\Service\FlashMessageServiceInterface` class. Although they are named the same as the methods
that used to be available through the `AbstractAction` class, they do have a slightly different signature. Before
Kickstart 3.0 both messages had a hard-coded flash message name in them - `message`. Since Kickstart 3.0, one can
define the flash message's name themselves.

So, you will need to replace usages of:

```php
$this->setFlashMessage('foo');
```

with:

```php
$this->setFlashMessage('message', 'foo');
```

and usages of:

```php
$this->getFlashMessage();
```

with:
```php
$this->getFlashMessage('message')
```

#### 8.6 The `getBaseHref()` Method

The `getBaseHref()` method needs to be replaced with calls to the `createFromRequest()` method of 
`Noctis\KickStart\Http\Factory\BaseHrefFactoryInterface`. The new method accepts one argument - an instance of
`Psr\Http\Message\ServerRequestInterface`, which is available in every action's `process()` method.

### 9. Migrating HTTP Middlewares/Guards

Compared to actions, middlewares/guards have seen fewer changes in Kickstart 3.0.0, so migration will be simpler.

One major thing is that the `Noctis\KickStart\Http\Middleware\AbstractMiddleware` abstract class has been removed, so
you will need to make two things:

* make sure none of your middlewares/guards extend it,
* make sure all of your middlewares/guards implement the `Psr\Http\Server\MiddlewareInterface` (same as actions).

If any of your middlewares/guards calls the `AbstractMiddleware::process()` method, like so:

```php
return parent::process($request, $handler);
```

make sure to replace it with this:

```php
return $handler->handle($request);
```

Also, if any of your middlewares/guards have an instance of the now defunct `ResponseFactoryInterface` class injected
into it - get rid of it.

### 10. Migrating Custom Requests

The `Noctis\KickStart\Http\Request\Request` class has been renamed to `AbstractRequest` in Kickstart 3.0.0, and it is 
now an abstract class, meaning it can't be instantiated on its own now, so you will have to change the name of the class
that your custom request classes extend.

The `getFiles()` method has been renamed to `getUploadedFiles()`, so that's another thing you'll need to locate and
correct.

Some additional methods have been added to the `AbstractRequest` class, i.e.:

* `getAttribute()`,
* `getParsedBody()`,
* `getQueryParams()`

Any calls to them are simply forwarded to the instance of `Psr\Http\Message\ServerRequestInterface` the 
`AbstractRequest` is decorating.

### 11. Migrating Away From `ResponseFactoryInterface`

One of the major changes introduced in Kickstart 3.0.0 was the removal of the
`Noctis\KickStart\Http\Response\ResponseFactoryInterface` interface and the `ResponseFactory` class which implements
it. They were broken down into smaller, dedicated response factories (in the `Noctis\KickStart\Http\Response\Factory`
namespace), each of which replaces one of the `ResponseFactoryInterface`'s methods:

| `ResponseFactoryInterface` method | Replacement method(s) <br/> (in `Noctis\KickStart\Http\Response\Factory` namespace) |
| --- | --- |
| `htmlResponse()` | `HtmlResponseFactoryInterface::render()` |
| `redirectionResponse()` | `RedirectResponseFactoryInterface::toPath()` |
| `fileResponse()` <br/> `attachmentResponse()` | `AttachmentResponseFactoryInterface::sendFile()`, <br/> `AttachmentResponseFactoryInterface::sendContent()`, or <br/> `AttachmentResponseFactoryInterface::sendResource()` |

You need to find uses of these `ResponseFactoryInterface` methods in your application and replace them with a use of
the appropriate new response factory.

### 12. Migrating Away From `FileInterface` & `FileResponse`

In Kickstart 2.3.0, the following interfaces and classes have been marked as deprecated:

* `Noctis\KickStart\File\FileInterface`,
* `Noctis\KickStart\File\File`,
* `Noctis\KickStart\File\InMemoryFile`, and
* `Noctis\KickStart\Http\Response\FileResponse`.

In Kickstart 3.0.0 those have been removed, so if your application is still using them, it's high time to migrate your
code. If your application uses neither of those, you can skip this section.

| Deprecated class | Replacement class |
| --- | --- |
| `Noctis\KickStart\File\FileInterface` | `Noctis\KickStart\Http\Response\Attachment\AttachmentInterface` |
| `Noctis\KickStart\File\File` <br/> `Noctis\KickStart\File\InMemoryFile` | `Noctis\KickStart\Http\Response\Attachment\Attachment` |
| `Noctis\KickStart\Http\Response\FileResponse` | `Noctis\KickStart\Http\Response\AttachmentResponse` |

To create a new instance of `Noctis\KickStart\File\File`, use the
`Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface::createFromPath()` method.

To create a new instance of `Noctis\KickStart\File\InMemoryFile`, use the
`Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface::createFromContent()` method.

### 13. Cherry on Top

Add the following to the `config` section in your `composer.json` file:

```json
"allow-plugins": {
    "composer/package-versions-deprecated": true,
    "squizlabs/php_codesniffer": true,
    "vimeo/psalm": true
}
```

Modify the Kickstart version indicator in your application's `composer.json` file, like so:

```json
"extra": {
    "app-version": "3.0.0"
},
```

Finally, execute the following command in CLI to update your `composer.lock` accordingly:

```sh
$ composer update --lock
```

and we're done!

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
  laminas/laminas-diactoros:^2.8 \
  noctis/kickstart:^3.0 \
  paragonie/easydb:^2.11 \
  php-di/php-di:^6.3 \
  psr/container:^1.1 \
  psr/http-message:^1.0 \
  psr/http-server-handler:^1.0 \
  symfony/console:^5.3 \
  symfony/http-foundation:^5.3 \
  vlucas/phpdotenv:^5.3
  ```
  
  ```shell
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
* Change the signature of their `process()` method from:
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
  * replace it with a reference to `Noctis\KickStart\Http\Helper\RedirectTrait`,
  * make sure an instance of `Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface` is injected
    into the trait's `$redirectResponseFactory` field (through the middleware's constructor).
* If the middleware uses the `App\Http\Helper\FlashMessageTrait` trait:
  * remove the reference to it from the middleware,
  * make sure an instance of `Noctis\KickStart\Http\Service\FlashMessageServiceInterface` is injected into the
    middleware, through its constructor,
  * use the `FlashMessageServiceInterface::setFlashMessage()` and `FlashMessageServiceInterface::getFlashMessage()`
    methods, in place of calls to the trait's `setFlashMessage()` and `getFlashMessage()` methods, respectively.
* Delete the `src/Http/Middleware/Guard/GuardMiddlewareInterface.php` file.

#### 4.2 HTTP Actions

* Rename & move the `src/Http/Routes/StandardRoutes.php` file to `src/Http/Routing/routes.php` (**notice the
  subdirectory name change, from `Routes` to `Routing`!**)..
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
      Route::get('/[{name}]', DummyAction::class, [DummyGuard::class]),
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
    ```
  * if the `execute()` method had dependencies injected into it, eg:
    ```php
    <?php declare(strict_types=1);
    namespace App\Http\Action;
  
    use App\Service\DummyServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
  
    final class DummyAction extends BaseAction
    {
        public function execute(DummyServiceInterface $dummyService): Response
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
  
    use App\Service\DummyServiceInterface;
    use Noctis\KickStart\Http\Action\ActionInterface;
  
    final class DummyAction implements ActionInterface
    {
        private DummyServiceInterface $dummyService;

        public function __construct(DummyServiceInterface $dummyService)
        {
            $this->dummyService = $dummyService;
        }
    
        // ...
    }
    ```
  * make sure `process()` method type-hints returning either `HtmlResponse`, `RedirectResponse`, `JsonResponse` or 
    `EmptyResponse` from the `Laminas\Diactoros\Response` namespace, e.g. `Laminas\Diactoros\Response\HtmlResponse`,
  * if the given action returns an HTML response, i.e. has the `return $this->render(...);` line:
    * include the `Noctis\KickStart\Http\Helper\RenderTrait` trait in it, and 
    * make sure an instance of `Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface` is injected into 
      the trait's `$htmlResponseFactory` field (through the action's constructor),
  * if the given action returns a redirection, i.e. has the `return $this->redirect(...);` line:
    * include the `Noctis\KickStart\Http\Helper\RedirectTrait` trait in it, and
    * make sure an instance of `Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface` is injected 
      into the trait's `$redirectResponseFactory` field (through the action's constructor),
  * if the given action returns (sends) a file in response:
    * include the `Noctis\KickStart\Http\Helper\AttachmentTrait` trait in it, and 
    * make sure an instance of `Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface` is injected
      into the trait's `$attachmentResponseFactory` field (through the action's constructor),
    * use one of the methods provided by the `AttachmentTrait`, accordingly to what you're sending to the browser:
      * `sendFile()` - if you want to send a file which already exists on a storage device,
      * `sendContent()` - if you want to send string data which is currently stored in a variable,
      * `sendResource()` - if you want to send a 
        [PHP resource](https://www.php.net/manual/en/language.types.resource.php).
  * if the given action returns a "not found" (404) response, i.e. has the `return $this->notFound();` line:
    * include the `Noctis\KickStart\Http\Helper\NotFoundTrait` trait in it, and
    * make sure an `Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactoryInterface` is injected into the 
      trait's `$notFoundResponseFactory` field (through the action's constructor).
* If there were no custom methods added to the `App\Http\Action\BaseAction` abstract class, delete the
  `src/Http/Action/BaseAction.php` file.
* Delete the `src/Http/Factory`, `src/Http/Helper` directories.
* Delete the `ActionInvoker.php`, `RequestHandlerInterface.php` and `RequestHandlerStack.php` files from the
  `src/Http/Middleware` directory.

#### 4.3 Migrating Custom Requests

* Edit any custom request classes in your application and make sure they now extend the
  `Noctis\KickStart\Http\Request\AbstractRequest` class, instead of the now non-existent 
  `App\Http\Request\AbstractRequest` one,
* Replace any calls to the request's `getFiles()` method with calls to the `getUploadedFiles()` method,
* The request's `getSession()` method has been removed in Kickstart 2.0.0. You can find more information on how to
  implement session handling in your application in the Kickstart's 
  [Implementing Session Handling](https://github.com/Noctis/kickstart-app/blob/master/docs/cookbook/Implementing_Session_Handling.md) 
  cookbook recipe,
* The request's `getClientIp()` method has also been removed in Kickstart 2.0.0. You can find more information on how to
  acquire the client's IP address in Kickstart's 
  [Acquiring Client IP Address](https://github.com/Noctis/kickstart-app/blob/master/docs/cookbook/Acquiring_Client_IP_Address.md)
  cookbook recipe,
* The request's `getBasePath()` method has been removed in Kickstart 3.0.0. Replace any calls to it with calls to the
  `createFromRequest()` method of the `Noctis\KickStart\Http\Factory\BaseHrefFactoryInterface`, for example replace 
  this (**notice:** the following code shows an action already migrated to Kickstart 3.0):
  ```php
  <?php

  declare(strict_types=1);

  namespace Bartender\Http\Action;

  use Bartender\Http\Request\DummyRequest;
  use Noctis\KickStart\Http\Action\ActionInterface;
  use Psr\Http\Message\ResponseInterface;
  use Psr\Http\Message\ServerRequestInterface;
  use Psr\Http\Server\RequestHandlerInterface;

  final class DummyAction implements ActionInterface
  {
      private DummyRequest $dummyRequest;

      /**
       * @param DummyRequest $dummyRequest
       */
      public function __construct(DummyRequest $dummyRequest)
      {
          $this->dummyRequest = $dummyRequest;
      }

      public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
      {
          $basePath = $this->request
              ->getBasePath();
        
          // ...
      }
  }
  ```
  like this:
  ```php
  <?php
  
  declare(strict_types=1);
  
  namespace Bartender\Http\Action;
  
  use Bartender\Http\Request\DummyRequest;
  use Noctis\KickStart\Http\Action\ActionInterface;
  use Noctis\KickStart\Http\Factory\BaseHrefFactoryInterface;
  use Psr\Http\Message\ResponseInterface;
  use Psr\Http\Message\ServerRequestInterface;
  use Psr\Http\Server\RequestHandlerInterface;
  
  final class DummyAction implements ActionInterface
  {
      private DummyRequest $dummyRequest;
      private BaseHrefFactoryInterface $baseHrefFactory;
  
      /**
       * @param DummyRequest $dummyRequest
       */
      public function __construct(DummyRequest $dummyRequest, BaseHrefFactoryInterface $baseHrefFactory)
      {
          $this->dummyRequest = $dummyRequest;
          $this->baseHrefFactory = $baseHrefFactory;
      }
  
      public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
      {
          $basePath = $this->baseHrefFactory
              ->createFromRequest($request);
  
          // ...
      }
  }
  ```

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
* Modify your `composer.json` file by adding the following `extra` section to it:
  ```json
  "extra": {
      "app-version": "3.0.0"
  }
  ```
* Run the following command in your console to update your `composer.lock` file:
  ```sh
  $ composer update --lock
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
  ```

  ```shell
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
* Rename & move the `src/Http/Routes/StandardRoutes.php` file to `src/Http/Routing/routes.php` (**notice the 
  subdirectory name change, from `Routes` to `Routing`!**).
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
      ['GET', '[{name}]', DummyAction::class, [DummyGuard::class]],
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
* Edit any `*Action.php` files within `src/Http/Action` directory (except `BaseAction.php`) and make sure:
  * those classes extend the `Noctis\KickStart\Http\Action\AbstractAction` abstract class,
  * their `execute()` methods type-hint returning either `HtmlResponse`, `RedirectResponse`, `JsonResponse` or
    `EmptyResponse` from the `Laminas\Diactoros\Response` namespace, e.g. `Laminas\Diactoros\Response\HtmlResponse`,
  * if given action sends an attachment (i.e. file) in response, you can use the `sendAttachment()` action.
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
* Modify your `composer.json` file by adding the following `extra` section to it:
  ```json
  "extra": {
      "app-version": "2.3.0"
  }
  ```
* Run the following command in your console to update your `composer.lock` file:
  ```sh
  $ composer update --lock
  ```