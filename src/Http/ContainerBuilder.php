<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use DI\ContainerBuilder as ActualContainerBuilder;
use DI\Definition\Helper\DefinitionHelper;
use DI\Definition\Reference;
use Noctis\KickStart\Provider\ConfigurationProvider;
use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Provider\StandardServicesProvider;
use Noctis\KickStart\Provider\TwigServiceProvider;
use Psr\Container\ContainerInterface;

use function DI\autowire;

final class ContainerBuilder
{
    private ActualContainerBuilder $containerBuilder;

    /**
     * ContainerBuilder constructor.
     */
    public function __construct()
    {
        $this->containerBuilder = new ActualContainerBuilder();

        $this
            ->registerServicesProvider(new ConfigurationProvider())
            ->registerServicesProvider(new HttpServicesProvider())
            ->registerServicesProvider(new TwigServiceProvider(
                (string)$_ENV['basepath']
            ))
            ->registerServicesProvider(new StandardServicesProvider())
        ;
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
