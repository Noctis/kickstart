<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

interface AutowireDefinitionInterface extends ContainerDefinitionInterface
{
    /**
     * @return class-string
     */
    public function getClassName(): string;

    /**
     * @return array<string, mixed>
     */
    public function getConstructorParameters(): array;
}
