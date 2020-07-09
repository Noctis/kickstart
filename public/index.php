<?php declare(strict_types=1);

use App\Http\Router;
use App\Http\Routes\StandardRoutes;
use function FastRoute\simpleDispatcher;

require_once __DIR__ .'/../bootstrap.php';

$dispatcher = simpleDispatcher(
    (new StandardRoutes())->get()
);

$container = get_container($env);
$router = new Router($dispatcher, $container);
$router->route();