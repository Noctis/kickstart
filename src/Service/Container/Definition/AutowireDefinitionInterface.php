<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

interface AutowireDefinitionInterface extends ContainerDefinitionInterface
{
    public function constructorParameter(string $name, mixed $value): self;

    public function method(string $methodName, mixed ...$values): self;
}
