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
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

use function FastRoute\simpleDispatcher;

final class FastRouteRouter implements RouterInterface
{
    /** @var list<RouteInterface> */
    private array $routes = [];

    public function addRoute(RouteInterface $route): RouterInterface
    {
        $this->routes[] = $route;

        return $this;
    }

    public function route(ServerRequestInterface $request): RouteInterface
    {
        $dispatcher = simpleDispatcher(
            function (RouteCollector $r): void {
                $r->addGroup(
                    Configuration::getBaseHref(),
                    function (RouteCollector $routeCollector): void {
                        foreach ($this->routes as $route) {
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
}
