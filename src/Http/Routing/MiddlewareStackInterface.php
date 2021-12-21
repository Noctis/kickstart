<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use IteratorAggregate;
use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareStackInterface extends IteratorAggregate
{
    /**
     * @param class-string<MiddlewareInterface> $name
     */
    public function addMiddleware(string $name): self;

    public function isEmpty(): bool;

    /**
     * @return class-string<MiddlewareInterface> | null
     */
    public function shift(): ?string;
}
