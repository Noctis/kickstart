<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

use function Psl\Str\strip_suffix;

final class Configuration
{
    private function __construct()
    {
    }

    public static function isProduction(): bool
    {
        return self::get('APP_ENV') === 'prod';
    }

    public static function getBaseHref(): string
    {
        /** @var string $baseHref */
        $baseHref = self::get('basehref');

        // Remove trailing slash ("/"), if applicable
        return strip_suffix($baseHref, '/');
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
        /** @psalm-suppress MixedAssignment */
        $_ENV[$name] = $value;
    }

    public static function has(string $name): bool
    {
        return array_key_exists($name, $_ENV);
    }
}
