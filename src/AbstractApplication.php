<?php declare(strict_types=1);
namespace Noctis\KickStart;

use Noctis\KickStart\Provider\ServicesProviderInterface;

abstract class AbstractApplication
{
    abstract public function run(): void;

    protected function getContainerBuilder(): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addServicesProviders(
            $this->getServiceProviders()
        );

        return $containerBuilder;
    }

    /**
     * @return ServicesProviderInterface[]
     */
    abstract protected function getServiceProviders(): array;
}