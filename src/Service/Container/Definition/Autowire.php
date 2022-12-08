<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

final class Autowire implements AutowireDefinitionInterface
{
    /**
     * @param class-string         $className
     * @param array<string, mixed> $constructorParameters
     */
    public function __construct(
        private readonly string $className,
        private readonly array  $constructorParameters
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @inheritDoc
     */
    public function getConstructorParameters(): array
    {
        return $this->constructorParameters;
    }
}
