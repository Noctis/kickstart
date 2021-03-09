<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing;

use DI\Container;
use FastRoute\Dispatcher;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandler;
use Noctis\KickStart\Http\Routing\Handler\RouteFoundHandler;
use Noctis\KickStart\Http\Routing\Handler\RouteNotFoundHandler;
use RuntimeException;

final class Router
{
    private ?Dispatcher $dispatcher;
    private Container $container;
    private RouteFoundHandler $routeFoundHandler;
    private RouteNotFoundHandler $routeNotFoundHandler;
    private MethodNotAllowedHandler $methodNotAllowedHandler;

    public function __construct(
        Container $container,
        RouteFoundHandler $routeFoundHandler,
        RouteNotFoundHandler $routeNotFoundHandler,
        MethodNotAllowedHandler $methodNotAllowedHandler
    ) {
        $this->dispatcher = null;
        $this->container = $container;
        $this->routeFoundHandler = $routeFoundHandler;
        $this->routeNotFoundHandler = $routeNotFoundHandler;
        $this->methodNotAllowedHandler = $methodNotAllowedHandler;
    }

    public function setDispatcher(Dispatcher $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }

    public function route(): void
    {
        $routeInfo = $this->determineRouteInfo();

        $response = match ($routeInfo[0]) {
            Dispatcher::FOUND              => $this->routeFoundHandler->handle($routeInfo),         // ... 200 Found
            Dispatcher::NOT_FOUND          => $this->routeNotFoundHandler->handle($routeInfo),      // ... 404 Not Found
            Dispatcher::METHOD_NOT_ALLOWED => $this->methodNotAllowedHandler->handle($routeInfo),   // ... 405 Method Not Allowed
            default                        => throw new RuntimeException(),
        };

        $response->send();
    }

    private function determineRouteInfo(): array
    {
        if (!isset($this->dispatcher)) {
            throw new RuntimeException('Router dispatcher not set. Did you forget to call setDispatcher()?');
        }

        /**
         * Fetch method and URI from somewhere
         * @var string $httpMethod
         */
        $httpMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
        /** @var string $uri */
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        return $this->dispatcher
            ->dispatch($httpMethod, $uri);
    }
}