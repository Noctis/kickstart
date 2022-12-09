<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container;

use Closure;
use Noctis\KickStart\Service\Container\Definition\AutowireDefinitionInterface;
use Noctis\KickStart\Service\Container\Definition\DecoratorDefinitionInterface;
use Noctis\KickStart\Service\Container\Definition\FactoryDefinitionInterface;
use Noctis\KickStart\Service\Container\Definition\ReferenceDefinitionInterface;
use Noctis\KickStart\Service\Container\PhpDi\Definition\Autowire;
use Noctis\KickStart\Service\Container\PhpDi\Definition\Decorator;
use Noctis\KickStart\Service\Container\PhpDi\Definition\Factory;
use Noctis\KickStart\Service\Container\PhpDi\Definition\Reference;

/**
 * @param class-string $className
 */
function autowire(string $className): AutowireDefinitionInterface
{
    return new Autowire($className);
}

function decorator(Closure $callable): DecoratorDefinitionInterface
{
    return new Decorator($callable);
}

function factory(Closure $callable): FactoryDefinitionInterface
{
    return new Factory($callable);
}

function reference(string $name): ReferenceDefinitionInterface
{
    return new Reference($name);
}
