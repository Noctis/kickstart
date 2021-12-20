<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;
use Psr\Http\Server\MiddlewareInterface;

final class MiddlewareStack implements IteratorAggregate, MiddlewareStackInterface
{
    /** @var list<class-string<MiddlewareInterface>> */
    private array $middlewareNames = [];

    public static function createFromRoute(RouteInterface $route): self
    {
        $stack = new self(
            $route->getMiddlewareNames()
        );
        $stack->addMiddleware(
            $route->getActionName()
        );

        return $stack;
    }

    /**
     * @param list<class-string<MiddlewareInterface>> $middlewareNames
     */
    public function __construct(array $middlewareNames)
    {
        foreach ($middlewareNames as $name) {
            $this->addMiddleware($name);
        }
    }

    /**
     * @inheritDoc
     */
    public function addMiddleware(string $name): MiddlewareStackInterface
    {
        if (!is_a($name, MiddlewareInterface::class, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Given class (%s) does not implement the %s interface.',
                    $name,
                    MiddlewareInterface::class
                )
            );
        }

        $this->middlewareNames[] = $name;

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->middlewareNames === [];
    }

    /**
     * @inheritDoc
     */
    public function shift(): ?string
    {
        return array_shift($this->middlewareNames);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->middlewareNames);
    }
}
