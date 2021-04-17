<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use Noctis\KickStart\Http\Routing\Handler\FoundHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\NotFoundHandlerInterface;
use RuntimeException;

final class Router
{
    private ?Dispatcher $dispatcher;
    private HttpInfoProviderInterface $httpInfoProvider;
    private FoundHandlerInterface $foundHandler;
    private NotFoundHandlerInterface $notFoundHandler;
    private MethodNotAllowedHandlerInterface $methodNotAllowedHandler;

    public function __construct(
        HttpInfoProviderInterface $httpInfoProvider,
        FoundHandlerInterface $foundHandler,
        NotFoundHandlerInterface $notFoundHandler,
        MethodNotAllowedHandlerInterface $methodNotAllowedHandler
    ) {
        $this->dispatcher = null;
        $this->httpInfoProvider = $httpInfoProvider;
        $this->foundHandler = $foundHandler;
        $this->notFoundHandler = $notFoundHandler;
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
            Dispatcher::FOUND              => $this->foundHandler->handle($routeInfo),              // ... 200
            Dispatcher::NOT_FOUND          => $this->notFoundHandler->handle($routeInfo),           // ... 404
            Dispatcher::METHOD_NOT_ALLOWED => $this->methodNotAllowedHandler->handle($routeInfo),   // ... 405
            default                        => throw new RuntimeException(),
        };
        $response->send();
    }

    private function determineRouteInfo(): array
    {
        if (!isset($this->dispatcher)) {
            throw new RuntimeException('Router dispatcher not set. Did you forget to call setDispatcher()?');
        }

        $httpMethod = $this->httpInfoProvider
            ->getMethod();
        $uri = $this->httpInfoProvider
            ->getUri();

        if ($httpMethod === null || $uri === null) {
            throw new RuntimeException(
                'Could not determine HTTP method and/or URI. Are you running in CLI?'
            );
        }

        return $this->dispatcher
            ->dispatch($httpMethod, $uri);
    }
}
