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
* Psalm inspection now includes looking for leftover calls to `dump()` function,
* Updated dependencies to their newest possible versions.
