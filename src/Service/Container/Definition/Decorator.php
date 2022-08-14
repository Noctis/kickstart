<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\Definition;

final class Decorator implements DecoratorDefinitionInterface
{
    /** @var callable */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function getCallable(): callable
    {
        return $this->callable;
    }
}
