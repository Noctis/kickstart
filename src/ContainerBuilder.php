<?php declare(strict_types=1);
namespace Noctis\KickStart;

use DI\Container;
use DI\ContainerBuilder as ActualContainerBuilder;
use DI\Definition\Helper\DefinitionHelper;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use function DI\autowire;

final class ContainerBuilder
{
    /** @var ServicesProviderInterface[] */
    private array $servicesProviders = [];

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

    public function build(): Container
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
                array_map(
                    function (string|callable|DefinitionHelper $definition): callable|DefinitionHelper {
                        if (is_string($definition)) {
                            return autowire($definition);
                        }

                        return $definition;
                    },
                    $servicesProvider->getServicesDefinitions()
                )
            );
        }
    }
}