<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

class Configuration implements ConfigurationInterface
{
    /** @var array<string, mixed> */
    private array $values = [];

    public function getBaseHref(): string
    {
        $baseHref = $this->get('basehref');

        // Remove trailing slash ("/"), if applicable
        if ($baseHref[-1] === '/') {
            $baseHref = substr($baseHref, 0, -1);
        }

        return $baseHref;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name, $default = null)
    {
        return $this->values[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, mixed $value): void
    {
        $this->values[$name] = $value;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->values);
    }
}
