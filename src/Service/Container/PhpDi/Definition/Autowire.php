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

    public function __invoke(): AutowireDefinitionHelper
    {
        $helper = autowire($this->className);
        foreach ($this->constructorParameters as $name => $value) {
            $helper = $helper->constructorParameter($name, $value);
        }

        return $helper;
    }
}
