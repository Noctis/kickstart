<?php declare(strict_types=1);
namespace Noctis\KickStart\Configuration;

interface ConfigurationLoaderInterface
{
    public function load(string $path, array $requirements = []): void;
}