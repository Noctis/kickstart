<?php

declare(strict_types=1);

namespace Noctis\KickStart;

use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Service\Container\PhpDi\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function Psl\Vec\concat;

trait BootableApplicationTrait
{
    /**
     * @param ServicesProviderInterface ...$servicesProviders
     */
    public static function boot(ServicesProviderInterface ...$servicesProviders): self
    {
        $container = self::buildContainer(
            ...concat(
                self::getObligatoryServiceProviders(),
                $servicesProviders
            )
        );

        return $container->get(self::class);
    }

    /**
     * @return list<ServicesProviderInterface>
     */
    protected static function getObligatoryServiceProviders(): array
    {
        return [];
    }

    private static function buildContainer(ServicesProviderInterface ...$servicesProviders): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        foreach ($servicesProviders as $servicesProvider) {
            $containerBuilder->registerServicesProvider($servicesProvider);
        }

        return $containerBuilder->build();
    }
}
