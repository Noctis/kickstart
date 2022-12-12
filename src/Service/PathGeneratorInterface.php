<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;

interface PathGeneratorInterface
{
    public function setRoutes(RoutesCollectionInterface $routes): self;

    /**
     * @param array<string, string|int> $params
     */
    public function generate(string $routeName, array $params = []): string;
}
