<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi\Definition;

use Closure;
use DI\Definition\Helper\FactoryDefinitionHelper;
use Noctis\KickStart\Service\Container\Definition\ContainerDefinitionInterface;

use function DI\factory;

final class Factory implements ContainerDefinitionInterface
{
    public function __construct(
        private readonly Closure $factory
    ) {
    }

    public function __invoke(): FactoryDefinitionHelper
    {
        return factory($this->factory);
    }
}
