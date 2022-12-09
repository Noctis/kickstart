<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi;

use DI\ContainerBuilder as ActualContainerBuilder;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Service\Container\ContainerBuilderInterface;
use Psr\Container\ContainerInterface;

use function Psl\Dict\map;

final class ContainerBuilder implements ContainerBuilderInterface
{
    private ActualContainerBuilder $containerBuilder;
    private DefinitionNormalizer $definitionNormalizer;

    public function __construct()
    {
        $this->containerBuilder = new ActualContainerBuilder();
        $this->definitionNormalizer = new DefinitionNormalizer();
    }

    public function registerServicesProvider(ServicesProviderInterface $servicesProvider): self
    {
        $this->containerBuilder
            ->addDefinitions(
                map(
                    $servicesProvider->getServicesDefinitions(),
                    $this->definitionNormalizer
                        ->normalize(...)
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
