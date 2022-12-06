<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

use Closure;

interface AutowireDefinitionInterface extends ContainerDefinitionInterface
{
    public function constructorParameter(
        string $name,
        string | int | float | bool | array | Closure | ContainerDefinitionInterface $value
    ): self;

    public function method(
        string $methodName,
        string | int | float | bool | array | Closure | ContainerDefinitionInterface $value
    ): self;
}
