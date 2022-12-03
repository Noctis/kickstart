<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use InvalidArgumentException;
use Psl\DataStructure\Queue;
use Psl\DataStructure\QueueInterface;
use Psr\Http\Server\MiddlewareInterface;

final class MiddlewareStack implements MiddlewareStackInterface
{
    /** @var QueueInterface<class-string<MiddlewareInterface>> */
    private QueueInterface $queue;

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
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->queue = new Queue();
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

        $this->queue
            ->enqueue($name);

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->queue->peek() === null;
    }

    /**
     * @inheritDoc
     */
    public function shift(): ?string
    {
        return $this->queue
            ->pull();
    }
}
