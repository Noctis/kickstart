<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container;

use DI\Container;
use Psr\Container\ContainerInterface;

final class PhpDiContainer implements ContainerInterface, SettableContainerInterface
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        return $this->container
            ->get($id);
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->container
            ->has($id);
    }

    public function set(string $id, mixed $entry): void
    {
        $this->container
            ->set($id, $entry);
    }
}
