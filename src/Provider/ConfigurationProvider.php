<?php

declare(strict_types=1);

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

                /** @var string $value */
                foreach ($_ENV as $name => $value) {
                    $configuration->set(
                        (string)$name,
                        match ($value) {
                            'true' => true,
                            'false' => false,
                            default => $value
                        }
                    );
                }

                return $configuration;
            },
        ];
    }
}
