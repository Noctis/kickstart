<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use IteratorAggregate;

interface RoutesCollectionInterface extends IteratorAggregate
{
    public function addRoute(RouteInterface $route): self;
}
