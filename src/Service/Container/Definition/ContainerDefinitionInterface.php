<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

interface ContainerDefinitionInterface
{
    public function __invoke(): mixed;
}
