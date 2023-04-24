# 4.0.1

* Fixed a bug where URLs generated in Twig templates, using the `route()` function, were absolute, i.e. always had a
  forward slash (`/`) in front of them; all routes generated this way will now always have their leading slash trimmed,
  except for the root route (`/`).

# 4.0.0

* Min. required version of PHP is now 8.1,
* Standard & custom request classes now implement 
  [PSR-7](https://www.php-fig.org/psr/psr-7/#321-psrhttpmessageserverrequestinterface),
* (Experimental) DIC builder functions are now vendor-agnostic,
* Switched to using [PSL (PHP Standard Library)](https://github.com/azjezz/psl) instead of native PHP functions (where
  possible),
* Introduced named routes functionality,
* Removed `get()` method from HTTP 
  [`Request` class](https://github.com/Noctis/kickstart/blob/4.0.0/src/Http/Request/Request.php) & replaced it with two 
  new methods: `fromQueryString()` & `fromBody()`,
* Response factories now implement [PSR-17](https://www.php-fig.org/psr/psr-17/#22-responsefactoryinterface),
* DIC compilation functionality has been removed due to issues in certain popular scenarios,
* Psalm inspection now includes looking for leftover calls to `dump()` function,
* Updated dependencies to their newest possible versions.
