<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use DI\Definition\Definition;
use DI\Definition\Helper\DefinitionHelper;

interface ServicesProviderInterface
{
    /**
     * @psalm-return array<string, string|callable|DefinitionHelper|Definition>
     */
    public function getServicesDefinitions(): array;
}
