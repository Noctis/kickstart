<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Service\Container\Definition\ContainerDefinitionInterface;

interface ServicesProviderInterface
{
    /**
     * @psalm-return array<string, string | callable | array | ContainerDefinitionInterface>
     */
    public function getServicesDefinitions(): array;
}
