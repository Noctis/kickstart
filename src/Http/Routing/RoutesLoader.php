<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\RouteCollector;
use InvalidArgumentException;
use Noctis\KickStart\Configuration\ConfigurationInterface;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

final class RoutesLoader implements RoutesLoaderInterface
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function load(array $routes): callable
    {
        return function (RouteCollector $r) use ($routes): void {
            $r->addGroup(
                $this->configuration
                    ->getBaseHref(),
                function (RouteCollector $r) use ($routes) {
                    foreach ($routes as $definition) {
                        $this->loadRouteDefinition($r, $definition);
                    }
                }
            );
        };
    }

    private function loadRouteDefinition(RouteCollector $r, array $definition): void
    {
        [$method, $url, $action] = $definition;
        if (count($definition) === 4) {
            $guards = $definition[3];
        } else {
            $guards = [];
        }

        $this->addRoute($r, $method, $url, $action, $guards);
    }

    /**
     * @param class-string<AbstractAction>                 $action
     * @param list<class-string<AbstractMiddleware>>|empty $guards
     */
    private function addRoute(RouteCollector $r, string $method, string $url, string $action, array $guards): void
    {
        switch (strtolower($method)) {
            case 'get':
                $r->get($url, [$action, $guards]);
                break;

            case 'post':
                $r->post($url, [$action, $guards]);
                break;

            default:
                throw new InvalidArgumentException(
                    sprintf(
                        'Unsupported HTTP method name found in route definition: "%s".',
                        $method
                    )
                );
        }
    }
}
