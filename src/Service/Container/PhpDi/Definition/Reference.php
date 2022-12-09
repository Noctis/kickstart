<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi\Definition;

use Noctis\KickStart\Service\Container\Definition\ReferenceDefinitionInterface;

use function DI\get;

final class Reference implements ReferenceDefinitionInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return \DI\Definition\Reference
     */
    public function __invoke(): \DI\Definition\Reference
    {
        return get($this->name);
    }
}
