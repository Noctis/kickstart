<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Router;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Noctis\KickStart\Configuration\Configuration;
use Noctis\KickStart\Http\Action\MethodNotAllowedAction;
use Noctis\KickStart\Http\Action\NotFoundAction;
use Noctis\KickStart\Http\Routing\Route;
use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

use function FastRoute\simpleDispatcher;

final class FastRouteRouter implements RouterInterface
{
    private ?RoutesCollectionInterface $routes = null;

    public function setRoutes(RoutesCollectionInterface $routesCollection): RouterInterface
    {
        $this->routes = $routesCollection;

        return $this;
    }

    public function route(ServerRequestInterface $request): RouteInterface
    {
        if ($this->routes === null) {
            throw new RuntimeException('Routes have not been set. Did you forget to call `setRoutes()`?');
        }

        $dispatcher = $this->getDispatcher($this->routes);
        $method = $request->getMethod();
        $requestedPath = $request->getUri()
            ->getPath();

        $dispatchInfo = $dispatcher->dispatch($method, $requestedPath);

        /** @var RouteInterface $route */
        $route = match ($dispatchInfo[0]) {
            Dispatcher::FOUND => $dispatchInfo[1],
            Dispatcher::NOT_FOUND => new Route($method, $requestedPath, NotFoundAction::class),
            Dispatcher::METHOD_NOT_ALLOWED => new Route($method, $requestedPath, MethodNotAllowedAction::class),
            default => throw new RuntimeException(),
        };

        if ($dispatchInfo[0] === Dispatcher::FOUND) {
            /** @var array<string, string> $additionalVars */
            $additionalVars = $dispatchInfo[2];
            $route = $route->withAdditionalVars($additionalVars);
        }

        return $route;
    }

    private function getDispatcher(RoutesCollectionInterface $routes): Dispatcher
    {
        return simpleDispatcher(
            function (RouteCollector $r) use ($routes): void {
                $r->addGroup(
                    Configuration::getBaseHref(),
                    function (RouteCollector $routeCollector) use ($routes): void {
                        /** @var RouteInterface $route */
                        foreach ($routes as $route) {
                            $routeCollector->addRoute(
                                $route->getMethod(),
                                $route->getPath(),
                                $route
                            );
                        }
                    }
                );
            }
        );
    }
}
