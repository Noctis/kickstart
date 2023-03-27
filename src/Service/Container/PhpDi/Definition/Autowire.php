<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi\Definition;

use DI\Definition\Helper\AutowireDefinitionHelper;
use Noctis\KickStart\Service\Container\Definition\AutowireDefinitionInterface;
use Noctis\KickStart\Service\Container\Definition\ContainerDefinitionInterface;

use function DI\autowire;
use function Psl\Vec\map;

final class Autowire implements AutowireDefinitionInterface
{
    /** @var array<string, mixed> */
    private array $constructorParameters = [];

    /** @var array<string, list<mixed>> */
    private array $methodParameters = [];

    /**
     * @param class-string $className
     */
    public function __construct(
        private readonly string $className
    ) {
    }

    public function constructorParameter(string $name, mixed $value): AutowireDefinitionInterface
    {
        $this->constructorParameters[$name] = $value;

        return $this;
    }

    public function method(string $methodName, mixed ...$values): AutowireDefinitionInterface
    {
        $this->methodParameters[$methodName] = array_values($values);

        return $this;
    }

    public function __invoke(): AutowireDefinitionHelper
    {
        $helper = autowire($this->className);
        /** @psalm-suppress MixedAssignment */
        foreach ($this->constructorParameters as $name => $value) {
            $helper = $helper->constructorParameter(
                $name,
                $this->resolve($value)
            );
        }
        foreach ($this->methodParameters as $methodName => $values) {
            $helper = $helper->method(
                $methodName,
                ...map(
                    $values,
                    $this->resolve(...)
                )
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
