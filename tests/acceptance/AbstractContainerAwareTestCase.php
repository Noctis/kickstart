<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Provider\StandardServicesProvider;
use Noctis\KickStart\Provider\TwigServiceProvider;
use Noctis\KickStart\Service\Container\PhpDi\ContainerBuilder;
use Noctis\KickStart\Service\Container\SettableContainerInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractContainerAwareTestCase extends TestCase
{
    protected SettableContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->buildContainer();
    }

    /**
     * @return list<ServicesProviderInterface>
     */
    protected function additionalServiceProviders(): array
    {
        return [];
    }

    private function buildContainer(): SettableContainerInterface
    {
        $providers = $this->getServiceProviders();
        $builder = new ContainerBuilder();
        foreach ($providers as $provider) {
            $builder->registerServicesProvider($provider);
        }

        return $builder->build()
            ->get(SettableContainerInterface::class);
    }

    /**
     * @return non-empty-list<ServicesProviderInterface>
     */
    private function getServiceProviders(): array
    {
        return array_merge(
            $this->additionalServiceProviders(),
            [
                new HttpServicesProvider(),
                new TwigServiceProvider(),
                new StandardServicesProvider()
            ]
        );
    }
}
