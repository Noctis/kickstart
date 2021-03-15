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

                foreach ($_ENV as $name => $value) {
                    $configuration->set(
                        (string)$name,
                        $this->normalizeValue($value)
                    );
                }

                return $configuration;
            },
        ];
    }

    private function normalizeValue(mixed $value): mixed
    {
        return match ($value) {
            'true'  => true,
            'false' => false,
            default => $value
        };
    }
}
