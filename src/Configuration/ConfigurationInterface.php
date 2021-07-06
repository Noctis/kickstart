<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

interface ConfigurationInterface
{
    public static function isProduction(): bool;

    public static function getBaseHref(): string;

    public static function get(string $name, mixed $default = null): mixed;

    public static function set(string $name, mixed $value): void;

    public static function has(string $name): bool;
}
