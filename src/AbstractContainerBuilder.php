<?php

declare(strict_types=1);

namespace Noctis\KickStart;

use DI\ContainerBuilder as ActualContainerBuilder;
use DI\Definition\Helper\DefinitionHelper;
use DI\Definition\Reference;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Psr\Container\ContainerInterface;

use function DI\autowire;

abstract class AbstractContainerBuilder implements ContainerBuilderInterface
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
                /** @psalm-suppress MixedReturnTypeCoercion */
                    function (
                        string | callable | DefinitionHelper | Reference $definition
                    ): callable | DefinitionHelper | Reference {
                        if (is_string($definition)) {
                            $definition = autowire($definition);
                        }

                        /** @psalm-suppress MixedReturnTypeCoercion */
                        return $definition;
                    },
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
}