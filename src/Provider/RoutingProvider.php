<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Http\Routing\Router\FastRouteRouter;
use Noctis\KickStart\Http\Routing\Router\RouterInterface;

final class RoutingProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            RouterInterface::class => FastRouteRouter::class,
        ];
    }
}
