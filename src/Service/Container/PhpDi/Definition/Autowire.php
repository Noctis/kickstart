<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi\Definition;

use DI\Definition\Helper\AutowireDefinitionHelper;
use Noctis\KickStart\Service\Container\Definition\AutowireDefinitionInterface;

use function DI\autowire;

final class Autowire implements AutowireDefinitionInterface
{
    /** @var class-string */
    private string $className;

    /** @var array<string, mixed> */
    private array $constructorParameters = [];

    /** @var array<string, mixed> */
    private array $methodParameters = [];

    /**
     * @param class-string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function constructorParameter(string $name, mixed $value): AutowireDefinitionInterface
    {
        $this->constructorParameters[$name] = $value;

        return $this;
    }

    public function method(string $methodName, mixed $value): AutowireDefinitionInterface
    {
        $this->methodParameters[$methodName] = $value;

        return $this;
    }

    public function __invoke(): AutowireDefinitionHelper
    {
        $helper = autowire($this->className);
        foreach ($this->constructorParameters as $name => $value) {
            $helper = $helper->constructorParameter($name, $value);
        }
        foreach ($this->methodParameters as $methodName => $value) {
            $helper = $helper->method($methodName, $value);
        }

        return $helper;
    }
}
