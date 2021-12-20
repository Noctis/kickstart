<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

final class RequestHandler implements RequestHandlerInterface, MiddlewareStackHandlerInterface
{
    private Container $container;
    private ?MiddlewareStackInterface $middlewareStack;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->middlewareStack = null;
    }

    public function setMiddlewareStack(MiddlewareStackInterface $middlewareStack): void
    {
        $this->middlewareStack = $middlewareStack;
    }

    /**
     * @throws RuntimeException If the stack has not been set, i.e. `setStack()` has not been called prior.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /*
         * Re-register the request object, as the previously-called middleware might've modified it, for example, by
         * calling its `setAttribute()` method.
         */
        $this->container
            ->set(ServerRequestInterface::class, $request);

        if ($this->middlewareStack === null) {
            throw new RuntimeException(
                'Stack not found. Did you forget to call the `setStack()` method?'
            );
        }

        $nextMiddleware = $this->middlewareStack
            ->shift();
        if ($nextMiddleware === null) {
            throw new RuntimeException('Middleware stack is empty.');
        }

        $middleware = $this->getMiddleware($nextMiddleware);

        return $middleware->process($request, $this);
    }

    /**
     * @param class-string<MiddlewareInterface> $name
     */
    private function getMiddleware(string $name): MiddlewareInterface
    {
        /** @var MiddlewareInterface */
        return $this->container
            ->get($name);
    }
}
