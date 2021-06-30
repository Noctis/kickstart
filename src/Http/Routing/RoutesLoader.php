<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\RouteCollector;
use Fig\Http\Message\RequestMethodInterface;
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

    /**
     * @inheritDoc
     */
    public function load(array $routes): callable
    {
        return function (RouteCollector $r) use ($routes): void {
            $r->addGroup(
                $this->configuration
                    ->getBaseHref(),
                function (RouteCollector $r) use ($routes) {
                    foreach ($routes as $definition) {
                        $this->addRoute(
                            $r,
                            $definition->getMethod(),
                            $definition->getPath(),
                            $definition->getAction(),
                            $definition->getGuards()
                        );
                    }
                }
            );
        };
    }

    /**
     * @param class-string<AbstractAction>           $action
     * @param list<class-string<AbstractMiddleware>> $guards
     */
    private function addRoute(RouteCollector $r, string $method, string $url, string $action, array $guards): void
    {
        switch (strtoupper($method)) {
            case RequestMethodInterface::METHOD_GET:
                $r->get($url, [$action, $guards]);
                break;

            case RequestMethodInterface::METHOD_POST:
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
