<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi;

use Closure;
use DI\ContainerBuilder as ActualContainerBuilder;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Service\Container\ContainerBuilderInterface;
use Noctis\KickStart\Service\Container\Definition\ContainerDefinitionInterface;
use Noctis\KickStart\Service\Container\PhpDi\Definition\Autowire;
use Noctis\KickStart\Service\Container\PhpDi\Definition\Factory;
use Psr\Container\ContainerInterface;

use function Psl\Dict\map;

final class ContainerBuilder implements ContainerBuilderInterface
{
    private readonly ActualContainerBuilder $containerBuilder;

    public function __construct()
    {
        $this->containerBuilder = new ActualContainerBuilder();
    }

    public function registerServicesProvider(ServicesProviderInterface $servicesProvider): self
    {
        $normalizedDefinitions = map(
            $servicesProvider->getServicesDefinitions(),
            fn (string | Closure | ContainerDefinitionInterface $definition) => $this->normalizeDefinition($definition)
        );

        $this->containerBuilder
            ->addDefinitions(
                map(
                    $normalizedDefinitions,
                    fn (ContainerDefinitionInterface $definition) => $definition()
                )
            );

        return $this;
    }

    public function build(): ContainerInterface
    {
        return $this->containerBuilder
            ->build();
    }

    /**
     * @param class-string|Closure|ContainerDefinitionInterface $definition
     */
    private function normalizeDefinition(
        string | Closure | ContainerDefinitionInterface $definition
    ): ContainerDefinitionInterface {
        if (is_string($definition)) {
            return new Autowire($definition);
        } elseif ($definition instanceof Closure) {
            return new Factory($definition);
        }

        return $definition;
    }
}
