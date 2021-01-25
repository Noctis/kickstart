<?php declare(strict_types=1);
namespace Noctis\KickStart;

use DI\ContainerBuilder as ActualContainerBuilder;
use DI\Definition\Helper\DefinitionHelper;
use InvalidArgumentException;
use Noctis\KickStart\Provider\DatabaseConnectionProvider;
use Noctis\KickStart\Provider\DummyServicesProvider;
use Noctis\KickStart\Provider\HttpMiddlewareProvider;
use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Provider\TwigServiceProvider;
use Psr\Container\ContainerInterface;
use function DI\autowire;

final class ContainerBuilder
{
    public function build(string $path, string $env): ContainerInterface
    {
        $builder = new ActualContainerBuilder();
        //$builder->useAnnotations(true);

        $this->registerServices(
            $builder,
            new HttpServicesProvider(),
            new HttpMiddlewareProvider(),
            new TwigServiceProvider($path, $env),
            new DatabaseConnectionProvider(),
            new DummyServicesProvider()
        );

        return $builder->build();
    }

    private function registerServices(
        ActualContainerBuilder $builder,
        ServicesProviderInterface ...$providers
    ): void {
        $actualDefinitions = [];
        foreach ($providers as $servicesProvider) {
            foreach ($servicesProvider->getServicesDefinitions() as $name => $serviceDefinition) {
                $actualDefinitions[$name] = $this->getActualDefinition($serviceDefinition);
            }
        }

        $builder->addDefinitions($actualDefinitions);
    }

    /**
     * @param string|array|callable|DefinitionHelper $serviceDefinition
     *
     * @return DefinitionHelper|string|callable
     * @throws InvalidArgumentException
     */
    private function getActualDefinition($serviceDefinition)
    {
        /**
         * @psalm-suppress RedundantConditionGivenDocblockType
         * @psalm-suppress LessSpecificReturnStatement
         */
        if (is_callable($serviceDefinition)) {
            return $serviceDefinition;
        } elseif (is_string($serviceDefinition)) {
            return autowire($serviceDefinition);
        } elseif (is_array($serviceDefinition)) {
            [$serviceDefinition, $constructorArguments] = $serviceDefinition;

            $autowiredDefinition = autowire($serviceDefinition);
            foreach ($constructorArguments as $argument => $value) {
                $autowiredDefinition->constructorParameter($argument, $value);
            }

            return $autowiredDefinition;
        } elseif ($serviceDefinition instanceof DefinitionHelper) {
            return $serviceDefinition;
        }

        throw new InvalidArgumentException('Unknown service definition type given.');
    }
}