<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

final class RoutesParser implements RoutesParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(array $routes): array
    {
        return array_map(
            function (array $route): RouteInterface {
                /**
                 * @var string                       $method
                 * @var string                       $path
                 * @var class-string<AbstractAction> $action
                 */
                [$method, $path, $action] = $route;

                $guards = [];
                if (count($route) === 4) {
                    /** @var list<class-string<AbstractMiddleware>> $guards */
                    $guards = $route[3];
                }

                return new Route($method, $path, $action, $guards);
            },
            $routes
        );
    }
}
