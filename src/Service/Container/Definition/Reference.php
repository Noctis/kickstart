<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

final class Reference implements ReferenceDefinitionInterface
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
