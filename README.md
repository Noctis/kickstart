# Kickstart Base

[![Type Coverage](https://shepherd.dev/github/Noctis/kickstart/coverage.svg)](https://shepherd.dev/github/Noctis/kickstart)

## What is it?

It's the system (base) part of the Kickstart project. The Kickstart project itself consists of two parts:

* the application part - the [`noctis/kickstart-app`](https://github.com/Noctis/kickstart-app) package,
* the system part - (this one).

This package contains all the system files a Kickstart application needs in order to run properly. This is the part of
the Kickstart project which the user should not modify, but is free to extend in the application.

## What's it good for?

Kickstart was created to be a base for building micro and small PHP applications, either Web- or CLI-based.

## What exactly does it do?

It provides you with base code which allows you to skip building basic stuff like handling HTTP requests & responses or 
database connections and go right into implementing "the meat" of your application, i.e. business logic.

## So... like a framework?

Yeah, you could say that. I won't call this project a framework though. Or a mini-framework for that matter. I don't
think it fits the definition of "framework" well enough to call it that.

## Why should I used Kickstart instead of Laravel, or Symfony?

Good question. Those projects are perfectly fine. In many aspects they're better than Kickstart. They offer way more 
functionality and have a bigger community. But... They're also pretty big and handling even a single request requires a 
lot of calls to different methods and functions, and I personally am not a fan of such overhead.

Now, don't get me wrong. A proper framework like the ones I mentioned above are perfectly fine for many use cases. For 
medium or large scale application I would NOT recommend Kickstart - I'd use one of them. But, for a small, relatively 
simple application I think they're just overkill.

## So... when should I use Kickstart?

If you want to create a micro or small working Web- or CLI-based PHP application, that runs some relatively static SQL 
queries against the database. For example: you want an application that has a one-field web form, takes the user's input 
from said form, runs it against the database and finally presents a list of rows that matched the provided value. OK, 
that's an extremely simple application example, but I think you get the idea.

## What components does Kickstart offer?

* routing, with optional and/or required named request params - based on 
  [FastRoute](https://github.com/nikic/FastRoute), 
  by [Nikita Popov](https://github.com/nikic),
* dependency-injection - based on 
  [PHP-DI](https://php-di.org/) 
  by [Matthieu Napoli](https://github.com/mnapoli),
* database connectivity - based on 
  [EasyDB](https://github.com/paragonie/easydb) 
  by the [Paragon Initiative Enterprises](https://paragonie.com/),
* HTTP requests & responses handling - based on 
  [Symfony's](https://symfony.com/) 
  [HttpFoundation](https://symfony.com/doc/5.2/components/http_foundation.html) 
  component,
* HTTP middleware - my own implementation, in accordance with the 
  [middleware part](https://www.php-fig.org/psr/psr-15/#12-middleware) of 
  [PSR-15](https://www.php-fig.org/psr/psr-15/)
* template engine - based on Symfony's [Twig 3](https://twig.symfony.com/doc/3.x/),
* CLI (console) - based on Symfony's 
  [Console](https://symfony.com/doc/5.2/components/console.html) component,
* configuration - based on the ever popular 
  [PHP dotenv](https://github.com/vlucas/phpdotenv) 
  by [Vance Lucas](https://github.com/vlucas).

These are all off-the-shelf free components, which I've "tied" together into a working skeleton application, so you 
don't have to.

## But why isn't there a XYZ component?

I thought about adding a couple more components, like CSV file handling, internationalization (i18n), mail sending... 
but I've decided to leave that to the end user. If you want, you're free to add them yourself. I'll include examples on
how to do that in the documentation.

## But I don't need a console component, or a database connectivity component!

You're free not to utilize them. Their existence won't slow your application down, at least not noticeably. If you 
really want to get rid of a certain component, i.e. completely, you'll find documentation on how to do that in the
"Cookbook" section of [Kickstart Application](https://github.com/Noctis/kickstart-app) documentation. 

## OK, so how do I install this thing?

This package is not meant to be installed separately. You should create a new project based on the
[`noctis/kickstart-app`](https://github.com/Noctis/kickstart-app) package. That will install this package.

## Wait. "kickstart-app"? I thought this project was called "kickstart"?

Yes, the project is called "Kickstart", but - again - there are two parts of it:

* the core system, called `noctis/kickstart`,
* the application, called `noctis/kickstart-app`.

`noctis/kickstart` is the base system, which gets installed into your `vendor` folder. This is what one could call 
"system space". That package's system (core) files:

* should **not** be modified by the user, and
* should be upgradeable (_via_ `composer update`).

`noctis/kickstart-app` is the skeleton app which was built upon the `noctis/kickstart` package. It contains:

* files which should be modifiable by the user, and
* example "dummy" files, showing you how to utilize the `noctis/kickstart`'s functionalities.

## That's dumb. There should be just one package, containing everything!

You're dumb (kidding). That's how it's used to be, back in the 1.x version of Kickstart.

A little history. After making yet another change to Kickstart, required by a specific application I've been working on, 
and then having to transpose those changes unto other Kickstart-based applications I was working on, I realized two things:

* updating files in one project by manually applying a subset of changes made in another one is stupid, especially 
  considering I have `composer update` at my disposal, and
* there are some files which the user should be able to modify, and some he/she should steer clear off.

If only those non-modifiable files could reside in the `vendor` folder, then they'd be easily updatable. Oh wait, they 
could be located there! So I started separating those two types of files. In the end, only of couple files/classes were 
left that had to be broken down because they belonged to both worlds. 

And that's how `noctis/kickstart` and `noctis/kickstart-app` packages were created.

## So, what happens when I need to update one of the system files?

Well, if you need to update the base system package, i.e. `noctis/kickstart`, all you need to do is run 

```shell
composer update noctis/kickstart`
```

If you need to update the skeleton application, i.e. `noctis/kickstart-app` - you will have to perform the update 
manually, if we're talking about an already existing application based on it.

## Why does it require PHP 8.0?

While working on the 2.x version of Kickstart (separate user & system packages) I decided to take advantage of the fact 
that PHP 8.0 recently came out and jump ahead of the curve. I could've made them work on PHP 7.4, but... I decided not to.
We'll have to move our code to PHP 8 eventually, might as well do it early.
