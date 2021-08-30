<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Fig\Http\Message\RequestMethodInterface;
use InvalidArgumentException;
use Noctis\KickStart\Configuration\Configuration;
use Noctis\KickStart\Http\Action\ActionInterface;
use Psr\Http\Server\MiddlewareInterface;

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
                                $route->getMiddlewareNames()
                            );
                        }
                    }
                );
            }
        );
    }

    /**
     * @param class-string<ActionInterface>           $action
     * @param list<class-string<MiddlewareInterface>> $middlewareNames
     */
    private function addRoute(
        RouteCollector $r,
        string $method,
        string $url,
        string $action,
        array $middlewareNames
    ): void {
        switch (strtoupper($method)) {
            case RequestMethodInterface::METHOD_GET:
                $r->get($url, [$action, $middlewareNames]);
                break;

            case RequestMethodInterface::METHOD_POST:
                $r->post($url, [$action, $middlewareNames]);
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
