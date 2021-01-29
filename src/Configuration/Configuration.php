<?php declare(strict_types=1);
namespace Noctis\KickStart\Configuration;

final class Configuration implements ConfigurationInterface
{
    /** @var array<string, mixed> */
    private array $values = [];

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