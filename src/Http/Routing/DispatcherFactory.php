<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Fig\Http\Message\RequestMethodInterface;
use InvalidArgumentException;
use Noctis\KickStart\Configuration\Configuration;
use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

use function FastRoute\simpleDispatcher;

final class DispatcherFactory implements DispatcherFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createFromArray(array $routes): Dispatcher
    {
        return simpleDispatcher(
            function (RouteCollector $r) use ($routes): void {
                $r->addGroup(
                    Configuration::getBaseHref(),
                    function (RouteCollector $r) use ($routes) {
                        foreach ($routes as $route) {
                            $this->addRoute(
                                $r,
                                $route->getMethod(),
                                $route->getPath(),
                                $route->getAction(),
                                $route->getGuards()
                            );
                        }
                    }
                );
            }
        );
    }

    /**
     * @param class-string<ActionInterface>          $action
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
