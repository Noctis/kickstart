<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use DI\Container;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use RuntimeException;

final class ActionInvoker implements ActionInvokerInterface
{
    private Container $container;

    /** @var array<class-string<MiddlewareInterface>> */
    private array $stack = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function setStack(array $stack): void
    {
        $this->stack = array_map(
            function (string $className): string {
                if (!is_a($className, MiddlewareInterface::class, true)) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'Given stack must contain only class names implementing the %s interface.',
                            MiddlewareInterface::class
                        )
                    );
                }

                return $className;
            },
            $stack
        );
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

        if ($this->stack === []) {
            throw new RuntimeException(
                'Stack not found. Did you forget to call the `setStack()` method?'
            );
        }

        $middleware = $this->getMiddleware(
            array_shift($this->stack)
        );

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
