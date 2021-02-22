<?php declare(strict_types=1);
namespace Noctis\KickStart;

use DI\ContainerBuilder as ActualContainerBuilder;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Psr\Container\ContainerInterface;

final class ContainerBuilder
{
    /** @var ServicesProviderInterface[] */
    private array $servicesProviders;

    /**
     * @param ServicesProviderInterface[] $providers
     */
    public function addServicesProviders(array $providers): void
    {
        array_map(
            function (ServicesProviderInterface $servicesProvider): void {
                $this->servicesProviders[] = $servicesProvider;
            },
            $providers
        );
    }

    public function build(): ContainerInterface
    {
        $builder = new ActualContainerBuilder();
        //$builder->useAnnotations(true);

        $this->registerServices($builder, ...$this->servicesProviders);

        return $builder->build();
    }

    private function registerServices(ActualContainerBuilder $builder, ServicesProviderInterface ...$providers): void
    {
        foreach ($providers as $servicesProvider) {
            $builder->addDefinitions(
                $servicesProvider->getServicesDefinitions()
            );
        }
    }
}