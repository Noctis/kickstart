#Kickstart

##What is it?

It's a relatively small code-base which allows one to start (kick-start, if you will) 
working on building a small PHP application.

##Who is it for?

Kickstart is best used for starting small Web-based and/or CLI applications, which 
utilize a relative database (RDBMS).

##What exactly does it do?

It provides one with a code base (skeleton code) that allows them to skip 
building the basic stuff like handling HTTP requests & responses or 
database connections and go right into implementing "the meat" of the application, ie.
business logic.

##So... like a framework?

Yeah, you could say that. I won't call this project a framework though. Or a 
mini-framework for that matter.

Why should I used it instead of Laravel, or Symfony?

Sure, those projects are fine. In many aspects they're better than Kickstart.
They offer more functionality and have a bigger community. But they're also 
pretty big and handling even a single request requires a lot of calls to
different methods and functions, and I personally am not a fan of that.  

Now, don't get me wrong. A proper framework like the ones I mentioned above are
perfectly fine for many uses. For a medium or large scale application I wouldn't
use Kickstart -  I'd use one of them. But, for a small, relatively simple
application I think they're simply overkill.

##So, when should I use Kickstart?

If you want to create a small, working web-browser or CLI based PHP application,
that runs some relatively static SQL queries against the database. For example:
you want an application that has a one-field web form, takes the user's input 
from  said form, runs it against the database and finally presents a list of rows
that match the provided value. OK, that's an example of an very simple application.
But you get the idea.

##What standard components does Kickstart offer?

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

These are all off-the-shelf free components, which I've tied together into a
working skeleton application.

##But why isn't there a XYZ component?

I thought about adding a couple more components, like CSV file handling,
internationalization (i18n), mail sending... but I decided to leave that to the
end user. If they want, they're free to add them themselves. I'll include 
examples on how to do that in the documentation.

##But I don't need a console component, or a database connectivity component!

You're free not to utilize them. Their existence won't slow your application
down, at least not noticeably. If you really want to get rid of a certain
component, i.e. completely, I'll include examples on how to do that in the
documentation.

##OK, so how do I install this thing?

You can use Composer to install the skeleton app (`kickstart-app`) and then modify it
to your needs.

One thing of notice here: replace `app-name` in the command below with whatever name
you want. `app-name` is the name of the folder which will be created in the current
working directory:

```
composer create-project noctis/kickstart-app app-name --repository='{"type":"vcs","url":"git@bitbucket.org:NoctisPL\/kickstart-app.git"}'
```

##Wait. "kickstart-app"? I thought this project was called "kickstart"?

Yes, the project is called "Kickstart", but it comes in two parts:

* the core system, called `kickstart`,
* the application, called `kickstart-app`.

`kickstart` is the base system, which gets installed into your `vendor` folder.
This is what one could call `system-space`. These system (core) files:

* should **not** be modified by the user, and
* should be upgrade'able (_via_ `composer update`).

`kickstart-app` is the skeleton app which utilizes the `kickstart` package.
It contains:

* files which should be modifiable by the user, and
* example "dummy" files, showing one how to utilize the core (`kickstart`) files.

##That's dumb. There should be just one package, containing everything.

You're dumb (kidding). That's how it's used to be, back in the 1.x version of 
Kickstart.

After making yet another change to the package, required by the specific
application I've been working on, and then having to transpose that update
unto other Kickstart-based applications I realized two things:

* updating files in one project by manually applying a subset of changes made 
  in another one is stupid, especially considering we have 
  `composer update` at our disposal, and
* there are some files which the user should be able to modify, and some he/she
  should steer clear off.
  
Now, if only those latter ones could be in the `vendor` folder, easily updatable. 
Oh wait, they can be! So I started separating those two types of files. 
In the end, only of couple files/classes were left that had to be broken down
because they belonged to both worlds. And that's how `kickstart` and 
`kickstart-app` packages were created.

##So, what happens when I need to update one of them?

Well, if you need to update the system package, i.e. `kickstart`, all you need
to do is run `composer update noctis/kickstart`.

If you need to update the application template, i.e. `kickstart-app` - you will
have to perform the update manually, if we're talking about an already existing
application based on it. Details on how to do that can be found in the 
UPDATING.md file.

##Why does it require PHP 8.0?

While working on the 2.x version of Kickstart (separate `kickstart` & 
`kickstart-app` packages) I decided to take advantage of the fact that PHP 8.0
recently came out and jump ahead of the curve. I could make them work on
PHP 7.4 but... no. We'll have to move our code to PHP 8 eventually, might as
well do it early.