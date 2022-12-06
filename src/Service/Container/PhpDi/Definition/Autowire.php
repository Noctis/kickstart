<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi\Definition;

use Closure;
use DI\Definition\Helper\AutowireDefinitionHelper;
use Noctis\KickStart\Service\Container\Definition\AutowireDefinitionInterface;
use Noctis\KickStart\Service\Container\Definition\ContainerDefinitionInterface;

use function DI\autowire;

final class Autowire implements AutowireDefinitionInterface
{
    /** @var array<string, string | int | float | bool | array | Closure | ContainerDefinitionInterface> */
    private array $constructorParameters = [];

    /** @var array<string, string | int | float | bool | array | Closure | ContainerDefinitionInterface> */
    private array $methodParameters = [];

    /**
     * @param class-string $className
     */
    public function __construct(
        private readonly string $className
    ) {
    }

    public function constructorParameter(
        string $name,
        string | int | float | bool | array | Closure | ContainerDefinitionInterface $value
    ): AutowireDefinitionInterface {
        $this->constructorParameters[$name] = $value;

        return $this;
    }

    public function method(
        string $methodName,
        string | int | float | bool | array | Closure | ContainerDefinitionInterface $value
    ): AutowireDefinitionInterface {
        $this->methodParameters[$methodName] = $value;

        return $this;
    }

    public function __invoke(): AutowireDefinitionHelper
    {
        $helper = autowire($this->className);
        foreach ($this->constructorParameters as $name => $value) {
            $helper = $helper->constructorParameter(
                $name,
                $this->resolve($value)
            );
        }
        foreach ($this->methodParameters as $methodName => $value) {
            $helper = $helper->method(
                $methodName,
                $this->resolve($value)
            );
        }

        return $helper;
    }

    private function resolve(mixed $value): mixed
    {
        if ($value instanceof ContainerDefinitionInterface) {
            return $value();
        }

        return $value;
    }
}
