<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\Http\Routing\Handler\FoundHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\NotFoundHandlerInterface;
use RuntimeException;

use function FastRoute\simpleDispatcher;

final class Router implements RouterInterface
{
    private RoutesLoaderInterface $routesLoader;
    private HttpInfoProviderInterface $httpInfoProvider;
    private FoundHandlerInterface $foundHandler;
    private NotFoundHandlerInterface $notFoundHandler;
    private MethodNotAllowedHandlerInterface $methodNotAllowedHandler;
    private EmitterInterface $responseEmitter;

    /** @var list<array> */
    private array $routes = [];

    public function __construct(
        RoutesLoaderInterface $routesLoader,
        HttpInfoProviderInterface $httpInfoProvider,
        FoundHandlerInterface $foundHandler,
        NotFoundHandlerInterface $notFoundHandler,
        MethodNotAllowedHandlerInterface $methodNotAllowedHandler,
        EmitterInterface $responseEmitter
    ) {
        $this->routesLoader = $routesLoader;
        $this->httpInfoProvider = $httpInfoProvider;
        $this->foundHandler = $foundHandler;
        $this->notFoundHandler = $notFoundHandler;
        $this->methodNotAllowedHandler = $methodNotAllowedHandler;
        $this->responseEmitter = $responseEmitter;
    }

    /**
     * @param list<array> $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    public function route(): void
    {
        $routeInfo = $this->determineRouteInfo();

        $response = match ($routeInfo[0]) {
            Dispatcher::FOUND              => $this->foundHandler->handle($routeInfo),              // ... 200
            Dispatcher::NOT_FOUND          => $this->notFoundHandler->handle($routeInfo),           // ... 404
            Dispatcher::METHOD_NOT_ALLOWED => $this->methodNotAllowedHandler->handle($routeInfo),   // ... 405
            default                        => throw new RuntimeException(),
        };

        $this->responseEmitter
            ->emit($response);
    }

    private function determineRouteInfo(): array
    {
        $dispatcher = simpleDispatcher(
            $this->routesLoader
                ->load($this->routes)
        );

        $httpMethod = $this->httpInfoProvider
            ->getMethod();
        $uri = $this->httpInfoProvider
            ->getUri();

        if ($httpMethod === null || $uri === null) {
            throw new RuntimeException(
                'Could not determine HTTP method and/or URI. Are you running in CLI?'
            );
        }

        return $dispatcher->dispatch($httpMethod, $uri);
    }
}
