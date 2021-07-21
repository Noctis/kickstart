<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\Router;
use Noctis\KickStart\Http\Routing\RouterInterface;

use function DI\autowire;

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
            RouterInterface::class => autowire(Router::class)
                ->constructorParameter(
                    'routes',
                    $this->routes
                ),
        ];
    }
}
