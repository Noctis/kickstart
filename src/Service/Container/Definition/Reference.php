<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

final class Reference implements ReferenceDefinitionInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
