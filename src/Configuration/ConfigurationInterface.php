<?php declare(strict_types=1);
namespace Noctis\KickStart\Configuration;

interface ConfigurationInterface
{
    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param mixed  $value
     */
    public function set(string $name, mixed $value): void;

    public function has(string $name): bool;
}