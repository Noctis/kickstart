<?php

declare(strict_types=1);

namespace Noctis\KickStart;

use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Service\Container\PhpDi\ContainerBuilder;
use Psr\Container\ContainerInterface;

trait BootableApplicationTrait
{
    /**
     * @param list<ServicesProviderInterface> $servicesProviders
     */
    public static function boot(array $servicesProviders): self
    {
        /** @psalm-suppress MixedArgumentTypeCoercion */
        $container = self::buildContainer(
            array_merge(
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

    /**
     * @param list<ServicesProviderInterface> $servicesProviders
     */
    private static function buildContainer(array $servicesProviders): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        array_map(
            function (ServicesProviderInterface $serviceProvider) use ($containerBuilder): void {
                $containerBuilder->registerServicesProvider($serviceProvider);
            },
            $servicesProviders
        );

        return $containerBuilder->build();
    }
}
