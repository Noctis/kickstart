<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
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
    private EmitterInterface $responseEmitter;

    public function __construct(
        HttpInfoProviderInterface $httpInfoProvider,
        FoundHandlerInterface $foundHandler,
        NotFoundHandlerInterface $notFoundHandler,
        MethodNotAllowedHandlerInterface $methodNotAllowedHandler,
        EmitterInterface $responseEmitter
    ) {
        $this->dispatcher = null;
        $this->httpInfoProvider = $httpInfoProvider;
        $this->foundHandler = $foundHandler;
        $this->notFoundHandler = $notFoundHandler;
        $this->methodNotAllowedHandler = $methodNotAllowedHandler;
        $this->responseEmitter = $responseEmitter;
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

        $this->responseEmitter
            ->emit($response);
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
