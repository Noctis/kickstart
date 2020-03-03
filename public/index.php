<?php declare(strict_types=1);

use App\Http\Middleware\Guard\DummyGuard;
use App\Http\Router;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

require_once __DIR__ .'/../bootstrap.php';

$dispatcher = simpleDispatcher(
    function (RouteCollector $r) {
        $r->get('/[{name}]', [
            \App\Http\Action\DummyAction::class,
            [
                DummyGuard::class,
            ],
        ]);

        /*$r->addGroup(
            '/app',
            function (\FastRoute\RouteCollector $r) {
                $r->get('/[{name}]', [\App\Http\Action\DummyAction::class]);
            }
        );*/
    }
);

$container = get_container($env);
$router = new Router($dispatcher, $container);
$router->route();