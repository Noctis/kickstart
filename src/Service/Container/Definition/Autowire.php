<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

final class Autowire implements AutowireDefinitionInterface
{
    /** @var class-string */
    private string $className;

    /** @var array<string, mixed> */
    private array $constructorParameters;

    /**
     * @param class-string         $className
     * @param array<string, mixed> $constructorParameters
     */
    public function __construct(string $className, array $constructorParameters)
    {
        $this->className = $className;
        $this->constructorParameters = $constructorParameters;
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
