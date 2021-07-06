<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

final class Configuration implements ConfigurationInterface
{
    public static function isProduction(): bool
    {
        return self::get('debug') === false;
    }

    public static function getBaseHref(): string
    {
        /** @var string $baseHref */
        $baseHref = self::get('basehref');

        // Remove trailing slash ("/"), if applicable
        if ($baseHref[-1] === '/') {
            $baseHref = substr($baseHref, 0, -1);
        }

        return $baseHref;
    }

    public static function get(string $name, mixed $default = null): mixed
    {
        /** @var mixed */
        $value = $_ENV[$name] ?? $default;

        return match ($value) {
            'true'  => true,
            'false' => false,
            default => $value
        };
    }

    public static function set(string $name, mixed $value): void
    {
        $_ENV[$name] = $value;
    }

    public static function has(string $name): bool
    {
        return array_key_exists($name, $_ENV);
    }
}
