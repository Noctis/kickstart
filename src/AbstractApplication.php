<?php declare(strict_types=1);
namespace Noctis\KickStart;

use DI\Container;
use Noctis\KickStart\Provider\ServicesProviderInterface;

abstract class AbstractApplication
{
    protected Container $container;

    public function __construct()
    {
        $this->container = $this->getContainerBuilder()
            ->build();
    }

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