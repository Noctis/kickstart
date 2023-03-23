<?php

declare(strict_types=1);

namespace Noctis\KickStart;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\Router\RouterInterface;
use Noctis\KickStart\Http\Routing\RoutesCollection;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Service\Container\PhpDi\ContainerBuilder;
use Noctis\KickStart\Service\Container\SettableContainerInterface;
use Psr\Container\ContainerInterface;

use function Psl\Vec\concat;

abstract class AbstractApplication
{
    protected function __construct(
        protected readonly SettableContainerInterface $container,
        protected readonly RouterInterface            $router,
    ) {
    }

    /**
     * @param ServicesProviderInterface ...$servicesProviders
     */
    public static function boot(ServicesProviderInterface ...$servicesProviders): static
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
     * @param array<int|string, RouteInterface> $routes
     */
    public function setRoutes(array $routes): void
    {
        $routes = new RoutesCollection($routes);
        $this->router
            ->setRoutes($routes);
        $this->container
            ->set('__routes', $routes);
    }

    /**
     * @return list<ServicesProviderInterface>
     */
    protected static function getObligatoryServiceProviders(): array
    {
        return [];
    }

    protected static function buildContainer(ServicesProviderInterface ...$servicesProviders): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        foreach ($servicesProviders as $servicesProvider) {
            $containerBuilder->registerServicesProvider($servicesProvider);
        }

        return $containerBuilder->build();
    }
}
