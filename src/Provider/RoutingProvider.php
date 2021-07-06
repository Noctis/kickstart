<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Http\Routing\Router;
use Noctis\KickStart\Http\Routing\RouterInterface;

use function DI\autowire;

final class RoutingProvider implements ServicesProviderInterface
{
    /** @var list<array> */
    private array $routes;

    /**
     * @param list<array> $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = array_map(
            fn (array $route): array => $route,
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
