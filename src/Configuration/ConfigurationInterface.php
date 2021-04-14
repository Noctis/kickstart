<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

interface ConfigurationInterface
{
    public function getBaseHref(): string;

    public function get(string $name, mixed $default = null): mixed;

    public function set(string $name, mixed $value): void;

    public function has(string $name): bool;
}
