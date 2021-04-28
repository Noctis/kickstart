<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

interface ConfigurationLoaderInterface
{
    /**
     * @param array<string, string> $requirements
     */
    public function load(string $path, array $requirements = []): void;
}
