<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi;

use DI\Container as ActualContainer;
use Noctis\KickStart\Service\Container\SettableContainerInterface;
use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface, SettableContainerInterface
{
    public function __construct(
        private readonly ActualContainer      $container,
        private readonly DefinitionNormalizer $definitionNormalizer = new DefinitionNormalizer()
    ) {
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
            ->set(
                $id,
                $this->definitionNormalizer
                    ->normalize($entry)
            );
    }
}
