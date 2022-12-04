<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi\Definition;

use Closure;
use DI\Definition\Helper\FactoryDefinitionHelper;
use Noctis\KickStart\Service\Container\Definition\DecoratorDefinitionInterface;

use function DI\decorate;

final class Decorator implements DecoratorDefinitionInterface
{
    public function __construct(
        private readonly Closure $callable
    ) {
    }

    public function __invoke(): FactoryDefinitionHelper
    {
        return decorate($this->callable);
    }
}
