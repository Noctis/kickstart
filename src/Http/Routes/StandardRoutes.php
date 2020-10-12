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
            $r->get('/[{name}]', [
                DummyAction::class,
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
        };
    }
}