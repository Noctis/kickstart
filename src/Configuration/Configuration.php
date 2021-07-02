<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

final class Configuration implements ConfigurationInterface
{
    /** @var array<string, mixed> */
    private array $values = [];

    public static function isProduction(): bool
    {
        return $_ENV['debug'] === 'false';
    }

    public function getBaseHref(): string
    {
        /** @var string $baseHref */
        $baseHref = $this->get('basehref');

        // Remove trailing slash ("/"), if applicable
        if ($baseHref[-1] === '/') {
            $baseHref = substr($baseHref, 0, -1);
        }

        return $baseHref;
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->values[$name] ?? $default;
    }

    public function set(string $name, mixed $value): void
    {
        $this->values[$name] = $value;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->values);
    }
}
