<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Configuration\Configuration;
use Noctis\KickStart\Configuration\ConfigurationInterface;

final class ConfigurationProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            ConfigurationInterface::class => function (): Configuration {
                $configuration = new Configuration();

                foreach ($_ENV as $name => $value) {
                    $configuration->set($name, $value);
                }

                return $configuration;
            },
        ];
    }
}