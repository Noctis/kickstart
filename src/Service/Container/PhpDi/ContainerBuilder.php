<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi;

use DI\ContainerBuilder as ActualContainerBuilder;
use DI\Definition\Definition;
use DI\Definition\Helper\AutowireDefinitionHelper;
use DI\Definition\Helper\DefinitionHelper;
use InvalidArgumentException;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Service\Container\ContainerBuilderInterface;
use Noctis\KickStart\Service\Container\Definition\Autowire;
use Noctis\KickStart\Service\Container\Definition\ContainerDefinitionInterface;
use Noctis\KickStart\Service\Container\Definition\Decorator;
use Noctis\KickStart\Service\Container\Definition\Reference;
use Psr\Container\ContainerInterface;

use function DI\autowire;
use function DI\decorate;
use function DI\get;

final class ContainerBuilder implements ContainerBuilderInterface
{
    private ActualContainerBuilder $containerBuilder;

    public function __construct()
    {
        $this->containerBuilder = new ActualContainerBuilder();
    }

    public function registerServicesProvider(ServicesProviderInterface $servicesProvider): self
    {
        $this->containerBuilder
            ->addDefinitions(
                array_map(
                    [$this, 'getBuilderDefinition'],
                    $servicesProvider->getServicesDefinitions()
                )
            );

        return $this;
    }

    public function build(): ContainerInterface
    {
        return $this->containerBuilder
            ->build();
    }

    private function getBuilderDefinition(
        string | callable | array | ContainerDefinitionInterface $implementation
    ): Definition | DefinitionHelper | callable {
        if (is_string($implementation)) {
            $definition = autowire($implementation);
        } elseif (is_array($implementation)) {
            /** @var array<string, mixed> $implementation */
            $definition = $this->autowire(null, $implementation);
        } elseif (is_callable($implementation)) {
            $definition = $implementation;
        } elseif ($implementation instanceof Autowire) {
            $definition = $this->autowire(
                $implementation->getClassName(),
                $implementation->getConstructorParameters()
            );
        } elseif ($implementation instanceof Decorator) {
            $definition = decorate(
                $implementation->getCallable()
            );
        } elseif ($implementation instanceof Reference) {
            $definition = get(
                $implementation->getName()
            );
        } else {
            throw new InvalidArgumentException('Unsupported implementation provided.');
        }

        return $definition;
    }

    /**
     * @param class-string|null    $className
     * @param array<string, mixed> $constructorParameters
     */
    private function autowire(?string $className, array $constructorParameters = []): AutowireDefinitionHelper
    {
        $definition = autowire($className);
        /** @psalm-suppress MixedAssignment */
        foreach ($constructorParameters as $name => $value) {
            if ($value instanceof ContainerDefinitionInterface) {
                $value = $this->getBuilderDefinition($value);
            }

            $definition->constructorParameter($name, $value);
        }

        return $definition;
    }
}
