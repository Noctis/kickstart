<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\Router\FastRouteRouter;
use Noctis\KickStart\Http\Routing\Router\RouterInterface;
use Psr\Container\ContainerInterface;

final class RoutingProvider implements ServicesProviderInterface
{
    /** @var list<RouteInterface> */
    private array $routes;

    /**
     * @param list<RouteInterface> $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = array_map(
            fn (RouteInterface $route): RouteInterface => $route,
            $routes
        );
    }

    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            RouterInterface::class => function (ContainerInterface $container): FastRouteRouter {
                $router = $container->get(FastRouteRouter::class);
                foreach ($this->routes as $route) {
                    $router->addRoute($route);
                }

                return $router;
            }
        ];
    }
}
